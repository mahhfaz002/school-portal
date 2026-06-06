<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Score Entry: {{ $class }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc ml-5 text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Class picker (admins / exam officers). Teachers are locked to their class. --}}
            <form method="GET" action="{{ route('scores.create') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <label class="text-xs font-bold text-gray-500 uppercase">Class</label>
                <select name="class" onchange="this.form.submit()" class="rounded-lg border-gray-300">
                    <option value="">— Select class —</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $class == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </form>

            <form action="{{ route('scores.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                @csrf

                <div class="p-6 bg-indigo-900 text-white flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-indigo-300">Select Subject</label>
                        <select name="subject_id" class="mt-1 bg-indigo-800 border-indigo-700 text-white rounded-lg focus:ring-white w-64" required>
                            @forelse($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @empty
                                <option value="">No subjects — add some first</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold">Max CA: {{ setting('ca_max_score', 40) }} | Max Exam: {{ setting('exam_max_score', 60) }}</p>
                        <p class="text-xs text-indigo-300 italic">{{ setting('current_term') }} • {{ setting('current_session') }}</p>
                    </div>
                </div>

                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="py-3 px-4 text-sm font-bold text-gray-600">Student Name</th>
                                <th class="py-3 px-4 text-sm font-bold text-gray-600 text-center">CA Score (40)</th>
                                <th class="py-3 px-4 text-sm font-bold text-gray-600 text-center">Exam Score (60)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($students as $student)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-gray-800">{{ $student->full_name }}</td>
                                <td class="py-4 px-4">
                                    <input type="number" name="scores[{{ $student->id }}][ca]"
                                           class="w-24 mx-auto block border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center font-bold"
                                           placeholder="0" min="0" max="{{ setting('ca_max_score', 40) }}">
                                </td>
                                <td class="py-4 px-4">
                                    <input type="number" name="scores[{{ $student->id }}][exam]"
                                           class="w-24 mx-auto block border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-center font-bold"
                                           placeholder="0" min="0" max="{{ setting('exam_max_score', 60) }}">
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-400 italic">Select a class above to load its students.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-8">
                        <button type="submit" @disabled($students->isEmpty()) class="w-full bg-indigo-600 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg transition disabled:opacity-40">
                            💾 Upload Subject Scores
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
