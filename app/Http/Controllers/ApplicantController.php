<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /** Public application form. */
    public function showForm()
    {
        return view('admission.form');
    }

    /** Handle a public application submission. */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'date_of_birth'     => 'required|date',
            'gender'            => 'required|string|max:20',
            'parent_name'       => 'required|string|max:255',
            'parent_phone'      => 'required|string|max:50',
            'parent_email'      => 'required|email|max:255',
            'desired_class'     => 'required|string|max:100',
            'passport'          => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'indigene_letter'   => 'nullable|file|mimes:pdf,jpg,jpeg|max:2048',
        ]);

        // Only persist real columns; store uploads on the public disk.
        $data = collect($validated)->except(['passport', 'birth_certificate', 'indigene_letter'])->all();
        $data['status'] = 'pending';
        $data['passport_path'] = $request->file('passport')->store('documents/passports');
        $data['birth_cert_path'] = $request->file('birth_certificate')->store('documents/certificates');
        if ($request->hasFile('indigene_letter')) {
            $data['indigene_letter_path'] = $request->file('indigene_letter')->store('documents/indigene');
        }

        Applicant::create($data);

        return back()->with('success', 'Application submitted successfully! Please expect our response via your email and/or phone contact.');
    }

    /** Admin review panel. */
    public function index()
    {
        $applicants = Applicant::where('status', 'pending')->latest()->get();
        return view('admission.admin', compact('applicants'));
    }

    public function approve($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => 'approved']);
        ActivityLog::record("Approved applicant: {$applicant->full_name}", 'admission.approve');

        return back()->with('success', "{$applicant->full_name}'s application was approved.");
    }

    public function reject(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update([
            'status' => 'rejected',
            'reason' => $request->input('reason'),
        ]);
        ActivityLog::record("Rejected applicant: {$applicant->full_name}", 'admission.reject');

        return back()->with('success', "{$applicant->full_name}'s application was rejected.");
    }
}
