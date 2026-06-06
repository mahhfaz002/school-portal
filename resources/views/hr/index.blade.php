<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💼 HR & Payroll Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Employee Overview</h3>
                <table class="w-full text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Position</th>
                            <th class="p-2 border">Base Salary</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($employees as $employee) --}}
                        <tr>
                            <td class="p-2 border">Jane Doe</td>
                            <td class="p-2 border">Teacher</td>
                            <td class="p-2 border">₦150,000</td>
                            <td class="p-2 border">
                                <button class="bg-green-500 text-white px-3 py-1 rounded">View Payslip</button>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
