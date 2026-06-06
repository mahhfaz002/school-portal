<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🎓 Student Portal: {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-indigo-600 p-6 rounded-2xl text-white shadow-lg">
                    <p class="text-indigo-100 text-sm">Current Class</p>
                    <h3 class="text-2xl font-bold">SSS 3 Gold</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl border shadow-sm">
                    <p class="text-gray-500 text-sm">Termly Average</p>
                    <h3 class="text-2xl font-bold text-gray-800">78.4%</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl border shadow-sm">
                    <p class="text-gray-500 text-sm">Fees Status</p>
                    <h3 class="text-2xl font-bold text-green-600">Cleared</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6 bg-gray-50 border-b">
                    <h3 class="font-bold text-gray-700">First Term Examination Results</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-sm uppercase">
                                <th class="pb-4">Subject</th>
                                <th class="pb-4">C.A (40)</th>
                                <th class="pb-4">Exam (60)</th>
                                <th class="pb-4">Total (100)</th>
                                <th class="pb-4">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 font-medium">Mathematics</td>
                                <td class="py-4">32</td>
                                <td class="py-4">45</td>
                                <td class="py-4 font-bold">77</td>
                                <td class="py-4 text-green-600 font-bold">A</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 font-medium">English Language</td>
                                <td class="py-4">28</td>
                                <td class="py-4">40</td>
                                <td class="py-4 font-bold">68</td>
                                <td class="py-4 text-blue-600 font-bold">B</td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
