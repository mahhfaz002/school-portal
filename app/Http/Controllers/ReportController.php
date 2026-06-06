<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Score;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function downloadPdf($studentId)
    {
        $student = Student::findOrFail($studentId);
        $scores = Score::where('student_id', $student->id)->get();
        $totalScores = $scores->sum(fn($s) => $s->ca_score + $s->exam_score);
        $average = $scores->count() > 0 ? $totalScores / $scores->count() : 0;

        // Load the view that contains the HTML for the report card
        $pdf = Pdf::loadView('reports.student_pdf', compact('student', 'scores', 'average'));

        // Download the file
        return $pdf->download($student->full_name . '_Report_Card.pdf');
    }
}
