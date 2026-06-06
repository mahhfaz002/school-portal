<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 Admission Review Panel
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-700">Pending Applications</h3>
                </div>

                <table class="w-full text-left">
                    <thead class="bg-gray-100 border-b">
                        <tr class="text-xs uppercase text-gray-500">
                            <th class="px-6 py-4">Applicant Name</th>
                            <th class="px-6 py-4">DOB</th>
                            <th class="px-6 py-4">Parent Name</th>
                            <th class="px-6 py-4">Desired Class</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applicants as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $applicant->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $applicant->date_of_birth }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $applicant->parent_name }}<br>
                                <span class="text-xs text-indigo-600">{{ $applicant->parent_email }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-indigo-700">{{ $applicant->desired_class }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <form action="{{ route('admission.approve', $applicant->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold hover:bg-green-200">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admission.reject', $applicant->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold hover:bg-red-200">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center p-8 text-gray-500 italic">No pending applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
