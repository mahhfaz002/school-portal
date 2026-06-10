<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Support\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        $data = collect($validated)->except(['passport', 'birth_certificate', 'indigene_letter'])->all();
        $data['status'] = 'pending';

        // Passport kept as base64 so it survives deploys (no object storage).
        $pp = $request->file('passport');
        $data['passport'] = 'data:'.$pp->getMimeType().';base64,'.base64_encode(file_get_contents($pp->getRealPath()));

        // Other documents on the public disk (PDFs etc.).
        $data['birth_cert_path'] = $request->file('birth_certificate')->store('documents/certificates');
        if ($request->hasFile('indigene_letter')) {
            $data['indigene_letter_path'] = $request->file('indigene_letter')->store('documents/indigene');
        }

        Applicant::create($data);

        return back()->with('success', 'Application submitted successfully! Please expect our response via your email and/or phone contact.');
    }

    /** ICT: show the admission application form. */
    public function createApplication()
    {
        return view('admission.apply', [
            'sections' => Sections::ALL,
            'classes'  => SchoolClass::orderBy('section')->orderBy('name')->get(),
        ]);
    }

    /**
     * ICT: store a new admission application (section + class + documents).
     * FSLC required for secondary, junior WAEC for senior secondary.
     */
    public function storeApplication(Request $request)
    {
        $isSenior = $request->input('section') === Sections::SENIOR;
        $isSecondary = in_array($request->input('section'), [Sections::JUNIOR, Sections::SENIOR], true);

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'date_of_birth'  => 'required|date|before:today',
            'gender'         => 'required|string|max:20',
            'parent_name'    => 'required|string|max:255',
            'parent_phone'   => 'required|string|max:50',
            'parent_email'   => 'required|email|max:255',
            'section'        => ['required', Rule::in(Sections::ALL)],
            'desired_class'  => 'required|string|max:100',
            'passport'           => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'birth_certificate'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'indigene_letter'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'fslc'               => ($isSecondary ? 'required' : 'nullable').'|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'junior_waec'        => ($isSenior ? 'required' : 'nullable').'|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $data = collect($validated)->except(['passport', 'birth_certificate', 'indigene_letter', 'fslc', 'junior_waec'])->all();
        $data['status'] = 'pending';

        $pp = $request->file('passport');
        $data['passport'] = 'data:'.$pp->getMimeType().';base64,'.base64_encode(file_get_contents($pp->getRealPath()));
        $data['birth_cert_path'] = $request->file('birth_certificate')->store('documents/certificates');
        if ($request->hasFile('indigene_letter')) $data['indigene_letter_path'] = $request->file('indigene_letter')->store('documents/indigene');
        if ($request->hasFile('fslc')) $data['fslc_path'] = $request->file('fslc')->store('documents/fslc');
        if ($request->hasFile('junior_waec')) $data['junior_waec_path'] = $request->file('junior_waec')->store('documents/jwaec');

        $applicant = Applicant::create($data);
        ActivityLog::record("Created admission application for {$applicant->full_name}", 'admission.apply');

        return redirect()->route('admission.apply')->with('success', "Application for {$applicant->full_name} submitted to the Registrar for approval.");
    }

    /** Registrar review panel. */
    public function index()
    {
        $applicants = Applicant::latest()->get();
        return view('admission.admin', compact('applicants'));
    }

    /**
     * Approve = admit: create a Student record with a unique admission number.
     */
    public function approve($id)
    {
        $applicant = Applicant::findOrFail($id);

        if ($applicant->status === 'admitted') {
            return back()->with('error', "{$applicant->full_name} has already been admitted.");
        }

        $admissionNumber = $this->generateAdmissionNumber();

        $student = Student::create([
            'full_name'        => $applicant->full_name,
            'admission_number' => $admissionNumber,
            'class_arm'        => $applicant->desired_class,
            'section'          => $applicant->section ?? Sections::fromClassName($applicant->desired_class),
            'parent_phone'     => $applicant->parent_phone,
            'email'            => $applicant->parent_email,
            'fees_balance'     => 0,
            'photo'            => $applicant->passport, // base64 carries over to ID card
        ]);

        $applicant->update(['status' => 'admitted', 'admission_number' => $admissionNumber]);

        ActivityLog::record("Admitted applicant {$applicant->full_name} as {$admissionNumber}", 'admission.approve');

        return back()->with('success', "{$applicant->full_name} admitted. Admission No: {$admissionNumber}. Student record created.");
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

    /**
     * PREFIX/YEAR/SEQ — e.g. MAH/2026/014. Unique across students.
     */
    private function generateAdmissionNumber(): string
    {
        $prefix = Str::upper(setting('admission_prefix', setting('school_acronym', 'SCH')));
        $year = date('Y');

        $n = Student::count() + 1;
        do {
            $seq = str_pad((string) $n, 3, '0', STR_PAD_LEFT);
            $number = "{$prefix}/{$year}/{$seq}";
            $n++;
        } while (Student::where('admission_number', $number)->exists());

        return $number;
    }
}
