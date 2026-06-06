<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎓 Student Portal: <span class="text-blue-600">{{ $student->full_name ?? 'Guest' }}</span>
            </h2>
            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border">
                ID: {{ $student->admission_number ?? 'N/A' }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(isset($error))
                <div class="bg-red-100 border-l-4 border-red-500 p-4 text-red-700 mb-6 rounded-r-lg">
                    {{ $error }}
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-b-4 border-blue-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Class</p>
                    <h3 class="text-2xl font-black">{{ $student->class_arm }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-b-4 border-green-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Attendance Rate</p>
                    <h3 class="text-2xl font-black text-green-600">{{ $attendanceRate }}%</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-b-4 border-yellow-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Average Score</p>
                    <h3 class="text-2xl font-black text-yellow-600">
                        {{ $scores->avg(fn($s) => $s->ca_score + $s->exam_score) ? number_format($scores->avg(fn($s) => $s->ca_score + $s->exam_score), 1) : '0' }}%
                    </h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-b-4 border-red-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Fees Balance</p>
                    <h3 class="text-2xl font-black text-red-600">₦{{ number_format($student->fees_balance, 2) }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Termly Results - 1st Term 2025/2026</h3>
                        <a href="{{ route('reports.download', $student->id) }}" class="flex items-center text-xs text-blue-600 font-bold hover:text-blue-900 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Report Card
                        </a>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-gray-100">
                            <tr class="text-xs uppercase text-gray-500">
                                <th class="px-6 py-3">Subject</th>
                                <th class="px-6 py-3 text-center">CA (40)</th>
                                <th class="px-6 py-3 text-center">Exam (60)</th>
                                <th class="px-6 py-3 text-center">Total</th>
                                <th class="px-6 py-3 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($scores as $score)
                            @php
                                $total = $score->ca_score + $score->exam_score;
                                $grade = match(true) {
                                    $total >= 70 => 'A',
                                    $total >= 60 => 'B',
                                    $total >= 50 => 'C',
                                    $total >= 40 => 'D',
                                    default => 'F',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $score->subject }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-600">{{ $score->ca_score }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-600">{{ $score->exam_score }}</td>
                                <td class="px-6 py-4 text-sm text-center font-black text-blue-600">{{ $total }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-black rounded-full
                                        {{ $grade == 'A' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $grade == 'B' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $grade == 'C' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $grade == 'F' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ $grade }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic text-sm">No results uploaded for this term yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="font-bold text-gray-700">Recent Payment Log</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($payments as $payment)
                        <div class="flex justify-between items-center pb-3 border-b last:border-0">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Term Fees Payment</p>
                                <p class="text-[10px] text-gray-400">{{ $payment->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-green-600">+ ₦{{ number_format($payment->amount, 2) }}</p>
                                <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded uppercase font-bold">{{ $payment->payment_method }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-400 italic text-sm py-4">No payment records found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
