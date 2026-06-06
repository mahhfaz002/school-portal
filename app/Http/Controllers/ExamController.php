<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->latest()->get();
        return view('exams.index', compact('quizzes'));
    }

    // Submit an exam attempt
    public function submitQuiz(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $score = 0;

        // Simple Automated Grading Logic
        foreach ($request->answers as $questionId => $studentAnswer) {
            $question = Question::find($questionId);
            if ($question->correct_answer == $studentAnswer) {
                $score += $question->marks;
            }
        }

        QuizResult::create([
            'student_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score_obtained' => $score,
        ]);

        return redirect()->route('dashboard')->with('success', 'Exam submitted successfully.');
    }
}
