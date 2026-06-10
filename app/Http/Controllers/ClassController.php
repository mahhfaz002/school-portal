<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Support\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('section')->orderBy('name')->withCount('teachers')->get()
            ->map(function ($c) {
                $c->student_count = Student::where('class_arm', $c->name)->count();
                return $c;
            });

        return view('classes.index', ['classes' => $classes, 'sections' => Sections::ALL]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255', Rule::unique('classes', 'name')],
            'section' => ['required', Rule::in(Sections::ALL)],
        ]);

        SchoolClass::create([
            'name'    => $data['name'],
            'section' => $data['section'],
            'level'   => $data['section'],
            'active'  => true,
        ]);

        return back()->with('success', "Class {$data['name']} created in {$data['section']}.");
    }

    public function toggle(SchoolClass $schoolClass)
    {
        $schoolClass->update(['active' => !$schoolClass->active]);

        return back()->with('success', "{$schoolClass->name} is now ".($schoolClass->active ? 'active' : 'inactive').'.');
    }
}
