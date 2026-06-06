<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class AdmissionDashboardController extends Controller
{
    public function index()
    {
        $applicants = Applicant::where('status', 'pending')->get();
        return view('admin.dashboard', compact('applicants'));
    }

    public function updateStatus(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);

        $request->validate([
            'status' => 'required|in:admitted,rejected',
            'reason' => 'required_if:status,rejected'
        ]);

        $applicant->update([
            'status' => $request->status,
            'reason' => $request->reason, // Ensure this column exists in DB
        ]);

        return back()->with('success', 'Application status updated.');
    }
}