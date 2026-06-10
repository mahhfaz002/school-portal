<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\FeeBill;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Show the payment form for a specific student
    public function create(Student $student)
    {
        $bills = $student->bills()->whereIn('status', ['unpaid', 'part'])->latest()->get();
        return view('payments.create', compact('student', 'bills'));
    }

    // Save the payment, clear the chosen bill, update balance, show receipt
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'fee_bill_id' => 'nullable|exists:fee_bills,id',
        ]);

        // 1. Record the payment history (linked to a bill if chosen).
        $payment = Payment::create([
            'student_id' => $student->id,
            'fee_bill_id' => $validated['fee_bill_id'] ?? null,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'] ?? null,
        ]);

        // 2. Apply to a specific bill if selected, recompute its status.
        if (!empty($validated['fee_bill_id'])) {
            $bill = FeeBill::where('id', $validated['fee_bill_id'])
                ->where('student_id', $student->id)
                ->first();
            if ($bill) {
                $bill->amount_paid = (float) $bill->amount_paid + (float) $validated['amount'];
                $bill->refreshStatus();
            }
        }

        // 3. Subtract the amount from the student's running balance.
        $student->decrement('fees_balance', $validated['amount']);

        // 4. Straight to the auto-generated receipt.
        return redirect()->route('payments.receipt', $payment->id)
            ->with('success', 'Payment recorded. Receipt generated below.');
    }
    public function show(Payment $payment)
    {
        $payment->load('student');
        $this->authorizeReceipt($payment);
        return view('payments.receipt', compact('payment'));
    }

    /** Downloadable PDF receipt (same access rules). */
    public function downloadReceipt(Payment $payment)
    {
        $payment->load('student');
        $this->authorizeReceipt($payment);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.receipt_pdf', compact('payment'));
        return $pdf->download('Receipt_'.str_pad($payment->id, 6, '0', STR_PAD_LEFT).'.pdf');
    }

    /**
     * A receipt is visible to finance/oversight staff (view_fees) or to the
     * student it belongs to — never to other students.
     */
    private function authorizeReceipt(Payment $payment): void
    {
        $user = auth()->user();
        $isStaff = $user->canManage('view_fees');
        $isOwner = $user->role === 'student'
            && $payment->student
            && $payment->student->email
            && $payment->student->email === $user->email;

        abort_unless($isStaff || $isOwner, 403);
    }
}
