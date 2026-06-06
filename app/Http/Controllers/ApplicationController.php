<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Class name fixed to match routes
class ApplicantController extends Controller
{
    // ==========================================
    // PUBLIC METHODS (For Parents/Applicants)
    // ==========================================

    // Display the public application form
    public function showForm()
    {
        return view('admission.form'); // This maps to resources/views/admission/form.blade.php
    }

    // Submit the application form
    public function submit(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255',
            'desired_class' => 'required|string',
        ]);

        Applicant::create($request->all());

        return back()->with('success', 'Application submitted successfully! We will contact you soon.');
    }

    // ==========================================
    // ADMIN METHODS (For Staff/Principal)
    // ==========================================

    // View all pending applications
    public function index()
    {
        $applicants = Applicant::where('status', 'pending')->latest()->get();
        return view('admission.admin', compact('applicants'));
    }

    // Approve an application and transfer to students table
    public function approve($id)
    {
        $applicant = Applicant::findOrFail($id);

        // 1. Create the Student record
        $student = Student::create([
            'full_name' => $applicant->full_name,
            'admission_number' => 'ADM/' . date('Y') . '/' . Str::random(5),
            'class_arm' => $applicant->desired_class,
            'email' => $applicant->parent_email,
            'fees_balance' => 0, // Assuming 0 balance on registration
        ]);

        // 2. (Optional) Create a User account for the parent to log in
        User::create([
            'name' => $applicant->parent_name,
            'email' => $applicant->parent_email,
            'password' => Hash::make('password123'), // Default password
            'role' => 'student', // Mapping parent to student role for dashboard access
        ]);

        // 3. Update applicant status
        $applicant->update(['status' => 'approved']);

        return back()->with('success', "Application approved. Student admitted with ID: {$student->admission_number}");
    }

    // Reject an application
    public function reject($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => 'rejected']);

        return back()->with('success', 'Application rejected.');
    }
}
