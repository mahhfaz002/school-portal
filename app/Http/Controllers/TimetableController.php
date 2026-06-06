<?php

namespace App\Http\Controllers;

use App\Models\TimetableSlot;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $timetable = TimetableSlot::orderBy('start_time')->get();
        return view('timetable.index', compact('timetable'));
    }

    public function store(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'class_arm' => 'required',
            'subject_name' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // 2. CONFLICT CHECK LOGIC
        $conflict = TimetableSlot::where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->where(function($query) use ($request) {
                $query->where('room_name', $request->room_name)
                      ->orWhere('teacher_name', $request->teacher_name)
                      ->orWhere('class_arm', $request->class_arm);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Conflict detected! Teacher, Room, or Class is booked.');
        }

        TimetableSlot::create($request->all());
        return back()->with('success', 'Lesson added to timetable.');
    }
}
