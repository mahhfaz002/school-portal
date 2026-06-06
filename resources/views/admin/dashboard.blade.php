<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👑 Administrator Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(isset($search) && $search)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg">
                    Searching for: <strong>{{ $search }}</strong>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <p class="text-gray-500 text-sm">Total Students</p>
                    <h3 class="text-3xl font-bold text-indigo-700">1,250</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <p class="text-gray-500 text-sm">Total Staff</p>
                    <h3 class="text-3xl font-bold text-gray-800">85</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <p class="text-gray-500 text-sm">Pending Admissions</p>
                    <h3 class="text-3xl font-bold text-yellow-600">12</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <p class="text-gray-500 text-sm">Total Revenue (Term)</p>
                    <h3 class="text-3xl font-bold text-green-600">₦8.2M</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Quick Management</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="p-4 bg-indigo-50 text-indigo-700 rounded-lg text-center hover:bg-indigo-100">
                        <i class="fa-solid fa-user-graduate text-2xl mb-2"></i><br>Manage Students
                    </a>
                    <a href="#" class="p-4 bg-purple-50 text-purple-700 rounded-lg text-center hover:bg-purple-100">
                        <i class="fa-solid fa-chalkboard-user text-2xl mb-2"></i><br>Manage Staff
                    </a>
                    <a href="#" class="p-4 bg-yellow-50 text-yellow-700 rounded-lg text-center hover:bg-yellow-100">
                        <i class="fa-solid fa-file-invoice-dollar text-2xl mb-2"></i><br>Financials
                    </a>
                    <a href="#" class="p-4 bg-green-50 text-green-700 rounded-lg text-center hover:bg-green-100">
                        <i class="fa-solid fa-clipboard-list text-2xl mb-2"></i><br>Results
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
