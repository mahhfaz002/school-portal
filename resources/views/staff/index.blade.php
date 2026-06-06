<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff Management</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-lg shadow-sm mb-8 border-t-4 border-indigo-500">
                <h3 class="font-bold mb-4">Add New Staff Member</h3>
                <form action="{{ route('staff.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name" class="border-gray-300 rounded-md shadow-sm" required>
                    <input type="email" name="email" placeholder="Email Address" class="border-gray-300 rounded-md shadow-sm" required>
                    <input type="password" name="password" placeholder="Password" class="border-gray-300 rounded-md shadow-sm" required>
                    <select name="role" class="border-gray-300 rounded-md shadow-sm" required>
                        <option value="teacher">Teacher</option>
                        <option value="accountant">Accountant</option>
                        <option value="exam_officer">Exam Officer</option>
                        <option value="principal">Principal</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold hover:bg-indigo-700">Create Account</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="p-3 font-bold">Name</th>
                                <th class="p-3 font-bold">Role</th>
                                <th class="p-3 font-bold">Assigned Class</th>
                                <th class="p-3 font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $member)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $member->name }}</td>
                                <td class="p-3"><span class="uppercase text-xs font-bold px-2 py-1 bg-gray-200 rounded">{{ $member->role }}</span></td>
                                <td class="p-3 text-blue-600 font-bold">{{ $member->class_assigned ?? 'Not Assigned' }}</td>
                                <td class="p-3">
                                    <form action="{{ route('staff.assign', $member) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <select name="class_assigned" class="text-xs border-gray-300 rounded p-1">
                                            @forelse($classes as $class)
                                                <option value="{{ $class }}">{{ $class }}</option>
                                            @empty
                                                <option value="">No classes available</option>
                                            @endforelse
                                        </select>
                                        <button class="bg-green-600 text-white text-xs px-2 py-1 rounded">Assign</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
