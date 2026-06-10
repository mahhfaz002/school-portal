<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payslip — {{ $payslip->staff->name ?? '' }}</h2>
            <a href="{{ route('payroll.index', ['month' => $payslip->month]) }}" class="text-sm text-gray-500 font-bold no-print">← Back to Payroll</a>
        </div>
    </x-slot>

    <style>@media print { nav, header, .no-print { display:none !important; } body { background:#fff !important; } }</style>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div id="slip" class="bg-white p-8 shadow-lg border">
                <div class="text-center border-b-2 border-black pb-4 mb-6">
                    <h1 class="text-2xl font-black uppercase">{{ setting('school_name','School') }}</h1>
                    <p class="text-xs">{{ setting('school_address') }}</p>
                    <p class="text-xs">{{ setting('school_phone') }} | {{ setting('school_email') }}</p>
                </div>
                <h2 class="text-center font-bold text-lg underline mb-6">STAFF PAYSLIP — {{ \Illuminate\Support\Carbon::parse($payslip->month.'-01')->format('F Y') }}</h2>

                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div>
                        <p><strong>Staff:</strong> {{ $payslip->staff->name }}</p>
                        <p><strong>Role:</strong> {{ ucwords(str_replace('_',' ', $payslip->staff->role ?? '')) }}</p>
                        <p><strong>Staff ID:</strong> {{ $payslip->staff->staff_id ?? '—' }}</p>
                    </div>
                    <div class="text-right">
                        <p><strong>Status:</strong> {{ strtoupper($payslip->status) }}</p>
                        <p><strong>Paid:</strong> {{ $payslip->paid_at?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>

                @php $cur = setting('currency_symbol','₦'); @endphp
                <table class="w-full border-collapse border border-black text-sm mb-4">
                    <tr class="bg-gray-100"><th class="border border-black p-2 text-left">Earnings</th><th class="border border-black p-2 text-right">Amount</th></tr>
                    <tr><td class="border border-black p-2">Basic Salary</td><td class="border border-black p-2 text-right">{{ $cur }}{{ number_format($payslip->basic_salary,2) }}</td></tr>
                    <tr><td class="border border-black p-2">Allowances</td><td class="border border-black p-2 text-right">{{ $cur }}{{ number_format($payslip->allowances,2) }}</td></tr>
                </table>
                <table class="w-full border-collapse border border-black text-sm mb-4">
                    <tr class="bg-gray-100"><th class="border border-black p-2 text-left">Deductions</th><th class="border border-black p-2 text-right">Amount</th></tr>
                    @forelse($payslip->deductions ?? [] as $d)
                        <tr><td class="border border-black p-2">{{ $d['nature'] ?? '' }}</td><td class="border border-black p-2 text-right">{{ $cur }}{{ number_format($d['amount'] ?? 0,2) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="border border-black p-2 text-center text-gray-400">No deductions</td></tr>
                    @endforelse
                    <tr><td class="border border-black p-2">Tax</td><td class="border border-black p-2 text-right">{{ $cur }}{{ number_format($payslip->tax,2) }}</td></tr>
                </table>
                <div class="flex justify-end">
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase">Net Salary</p>
                        <p class="text-2xl font-black text-green-700">{{ $cur }}{{ number_format($payslip->net_salary,2) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center no-print">
                <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold">🖨️ Print</button>
                <a href="{{ route('payroll.slip.pdf', $payslip) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold ml-2">⬇️ Download PDF</a>
            </div>
        </div>
    </div>
</x-app-layout>
