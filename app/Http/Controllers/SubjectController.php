<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // List all subjects and show the add form
    public function index()
    {
        $subjects = Subject::orderBy('name', 'asc')->get();
        return view('subjects.index', compact('subjects'));
    }

    // Save a new subject
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subjects,name|max:255',
        ]);

        Subject::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Subject added successfully!');
    }

    // Delete a subject
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->back()->with('success', 'Subject removed!');
    }
}
