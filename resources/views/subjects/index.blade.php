<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage School Subjects</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow sm:rounded-lg mb-6">
                <h3 class="font-bold mb-4">Add New Subject</h3>
                <form action="{{ route('subjects.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="e.g. Mathematics" class="flex-1 border-gray-300 rounded shadow-sm" required>
                    <button type="submit" style="background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                        + Add Subject
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3 border">S/N</th>
                            <th class="p-3 border">Subject Name</th>
                            <th class="p-3 border text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td class="p-3 border text-center">{{ $loop->iteration }}</td>
                                <td class="p-3 border font-bold">{{ $subject->name }}</td>
                                <td class="p-3 border text-center">
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500 italic">No subjects added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
