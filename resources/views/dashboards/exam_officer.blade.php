<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📝 Exam Officer Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Students</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $totalStudents }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subjects</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $subjectsCount }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Scores Recorded</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $scoresEntered }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('subjects.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition text-center font-bold text-blue-700">Manage Subjects</a>
                    <a href="{{ route('students.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition text-center font-bold text-blue-700">Students</a>
                    <a href="{{ route('attendance.report') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition text-center font-bold text-blue-700">Attendance Report</a>
                    <a href="{{ route('dashboard') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition text-center font-bold text-blue-700">Refresh</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
