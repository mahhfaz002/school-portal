@extends('layouts.app') @section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Pending Admissions</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4">Student Name</th>
                    <th class="p-4">Class</th>
                    <th class="p-4">Documents</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicants as $applicant)
                    <tr class="border-b">
                        <td class="p-4">
                            {{ $applicant->full_name }}<br>
                            <small class="text-gray-500">Parent: {{ $applicant->parent_name }} ({{ $applicant->parent_phone }})</small>
                        </td>
                        <td class="p-4">{{ $applicant->desired_class }}</td>
                        <td class="p-4 space-x-2">
                            <a href="{{ media_url($applicant->passport_path) }}" target="_blank" class="text-blue-600">Passport</a>
                            <a href="{{ media_url($applicant->birth_cert_path) }}" target="_blank" class="text-blue-600">Birth Cert</a>
                        </td>
                        <td class="p-4 flex space-x-2">
                            <form action="{{ route('admission.approve', $applicant->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm">Approve</button>
                            </form>
                            <form action="{{ route('admission.reject', $applicant->id) }}" method="POST">
                                @csrf
                                <input type="text" name="reason" placeholder="Reason" class="border rounded px-2 py-1 text-sm" required>
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
