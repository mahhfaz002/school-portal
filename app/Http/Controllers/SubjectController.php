<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Support\Sections;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    // List all subjects (grouped by section) and show the add form
    public function index()
    {
        $subjects = Subject::orderBy('section')->orderBy('name')->get();
        return view('subjects.index', ['subjects' => $subjects, 'sections' => Sections::ALL]);
    }

    // Save a new subject (with its section)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|unique:subjects,name|max:255',
            'section' => ['nullable', Rule::in(Sections::ALL)],
        ]);

        Subject::create(['name' => $data['name'], 'section' => $data['section'] ?? null]);

        return redirect()->back()->with('success', 'Subject added successfully!');
    }

    // Delete a subject
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->back()->with('success', 'Subject removed!');
    }
}
