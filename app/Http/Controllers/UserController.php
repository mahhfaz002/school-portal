<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all staff (Teachers, Accountants, etc.)
     */
    public function index()
    {
        // Fetch all users who are NOT students, grouped by their role
        $staff = User::where('role', '!=', 'student')->get();

        // Get unique classes from the Student table to populate the assignment dropdown
        $classes = Student::distinct()->pluck('class_arm');

        return view('staff.index', compact('staff', 'classes'));
    }

    /**
     * Update the teacher's assigned class.
     */
    public function assignClass(Request $request, User $user)
    {
        $validated = $request->validate([
            'class_assigned' => 'required|string',
        ]);

        $user->update([
            'class_assigned' => $validated['class_assigned']
        ]);

        return redirect()->back()->with('success', "{$user->name} has been assigned to {$request->class_assigned}.");
    }

    /**
     * Create a new staff account (For Principal/ICT Director use)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'class_assigned' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->back()->with('success', 'New staff account created successfully!');
    }
}
