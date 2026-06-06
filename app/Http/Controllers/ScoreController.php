<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\Score;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\ReportCardGenerated;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Class + subject score-entry sheet.
     * Teachers default to their assigned class; admins may pick one via ?class=.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $class = $request->query('class')
            ?? ($user->role === 'teacher' ? $user->class_assigned : null);

        $classes = Student::select('class_arm')->whereNotNull('class_arm')->distinct()->orderBy('class_arm')->pluck('class_arm');
        $subjects = Subject::orderBy('name')->get();

        $students = $class
            ? Student::where('class_arm', $class)->orderBy('full_name')->get()
            : collect();

        return view('scores.create', [
            'class' => $class ?: 'Select a class',
            'classes' => $classes,
            'subjects' => $subjects,
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $caMax = (int) setting('ca_max_score', 40);
        $examMax = (int) setting('exam_max_score', 60);

        $validated = $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'scores'        => 'required|array',
            'scores.*.ca'   => "nullable|numeric|min:0|max:$caMax",
            'scores.*.exam' => "nullable|numeric|min:0|max:$examMax",
        ]);

        $term = setting('current_term', '1st Term');
        $session = setting('current_session', '2025/2026');

        $updatedStudentIds = [];

        foreach ($request->scores as $studentId => $data) {
            $hasCa = isset($data['ca']) && $data['ca'] !== '';
            $hasExam = isset($data['exam']) && $data['exam'] !== '';
            if ($hasCa || $hasExam) {
                Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $validated['subject_id'],
                        'term'       => $term,
                        'session'    => $session,
                    ],
                    [
                        'ca_score'   => $data['ca'] ?? 0,
                        'exam_score' => $data['exam'] ?? 0,
                    ]
                );
                $updatedStudentIds[] = $studentId;
            }
        }

        // Notify linked student accounts that results were updated.
        if (!empty($updatedStudentIds)) {
            $students = Student::whereIn('id', $updatedStudentIds)->get();
            foreach ($students as $student) {
                $linked = User::where('email', $student->email)->first();
                $linked?->notify(new ReportCardGenerated());
            }
        }

        $subjectName = Subject::find($validated['subject_id'])?->name ?? 'subject';
        ActivityLog::record("Entered scores for {$subjectName}", 'scores.store');

        return redirect()->route('dashboard')->with('success', "Scores for {$subjectName} updated successfully!");
    }
}
