<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📅 Timetable Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Current Weekly Schedule</h3>
                <table class="w-full text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Day</th>
                            <th class="p-2 border">Class</th>
                            <th class="p-2 border">Subject</th>
                            <th class="p-2 border">Teacher</th>
                            <th class="p-2 border">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetable as $slot)
                        <tr>
                            <td class="p-2 border">{{ $slot->day_of_week }}</td>
                            <td class="p-2 border">{{ $slot->class_arm }}</td>
                            <td class="p-2 border">{{ $slot->subject_name }}</td>
                            <td class="p-2 border">{{ $slot->teacher_name }}</td>
                            <td class="p-2 border">{{ $slot->start_time }} - {{ $slot->end_time }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
