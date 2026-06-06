<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Show the payment form for a specific student
    public function create(Student $student)
    {
        return view('payments.create', compact('student'));
    }

    // Save the payment and update student balance
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'nullable|string'
        ]);

        // 1. Record the payment history
        Payment::create([
            'student_id' => $student->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
        ]);

        // 2. Subtract the amount from the student's fees_balance
        $student->decrement('fees_balance', $request->amount);

        return redirect()->route('students.index')->with('success', 'Payment recorded and balance updated!');
    }
    public function show(Payment $payment)
{
    // Load the student details along with the payment
    $payment->load('student');
    return view('payments.receipt', compact('payment'));
}
}
