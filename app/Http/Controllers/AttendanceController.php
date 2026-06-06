<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Show the attendance marking page.
     */
    public function index(Request $request)
    {
        $selectedClass = $request->query('class');
        $date = $request->query('date', date('Y-m-d'));

        // Get unique class arms for the dropdown
        $classes = Student::distinct()->pluck('class_arm');

        $students = [];
        if ($selectedClass) {
            $students = Student::where('class_arm', $selectedClass)
                ->with(['attendances' => function($query) use ($date) {
                    $query->where('attendance_date', $date);
                }])
                ->orderBy('full_name', 'asc')
                ->get();
        }

        return view('attendance.index', compact('students', 'classes', 'selectedClass', 'date'));
    }

    /**
     * Save the attendance data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|array',
        ]);

        foreach ($request->status as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'attendance_date' => $request->date,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully for ' . $request->date);
    }
    public function report(Request $request)
{
    $selectedClass = $request->query('class');
    $month = $request->query('month', date('m'));
    $year = $request->query('year', date('Y'));

    $classes = Student::distinct()->pluck('class_arm');
    $reportData = [];

    if ($selectedClass) {
        $students = Student::where('class_arm', $selectedClass)->get();

        foreach ($students as $student) {
            $present = Attendance::where('student_id', $student->id)
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->whereIn('status', ['present', 'late'])
                ->count();

            $totalDays = Attendance::whereHas('student', function($q) use ($selectedClass) {
                    $q->where('class_arm', $selectedClass);
                })
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->distinct('attendance_date')
                ->count();

            $reportData[] = [
                'name' => $student->full_name,
                'present' => $present,
                'total' => $totalDays,
                'percentage' => $totalDays > 0 ? round(($present / $totalDays) * 100, 1) : 0
            ];
        }
    }

    return view('attendance.report', compact('classes', 'reportData', 'selectedClass', 'month', 'year'));
}
}