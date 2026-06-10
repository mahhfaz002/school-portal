<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📚 School Subjects</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>@endif

            @can('manage_subjects')
            <div class="bg-white p-6 shadow sm:rounded-lg mb-6">
                <h3 class="font-bold mb-4">Add New Subject</h3>
                <form action="{{ route('subjects.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    @csrf
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Subject</label>
                        <input type="text" name="name" placeholder="e.g. Mathematics" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section</label>
                        <select name="section" class="w-full border-gray-300 rounded shadow-sm">
                            <option value="">All sections</option>
                            @foreach($sections as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-indigo-700 text-sm h-10">+ Add Subject</button>
                </form>
            </div>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3 border">S/N</th>
                            <th class="p-3 border">Subject</th>
                            <th class="p-3 border">Section</th>
                            @can('manage_subjects')<th class="p-3 border text-center">Action</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td class="p-3 border text-center">{{ $loop->iteration }}</td>
                                <td class="p-3 border font-bold">{{ $subject->name }}</td>
                                <td class="p-3 border text-sm text-gray-500">{{ $subject->section ?? 'All' }}</td>
                                @can('manage_subjects')
                                <td class="p-3 border text-center">
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Delete this subject?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-4 text-center text-gray-500 italic">No subjects added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
