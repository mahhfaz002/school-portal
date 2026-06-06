<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🚌 Transportation Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Active Routes & Vehicles</h3>
                <table class="w-full text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Route</th>
                            <th class="p-2 border">Vehicle</th>
                            <th class="p-2 border">Driver</th>
                            <th class="p-2 border">Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($routes as $route) --}}
                        <tr>
                            <td class="p-2 border">North Zone</td>
                            <td class="p-2 border">ABC-123</td>
                            <td class="p-2 border">John Doe</td>
                            <td class="p-2 border">15 / 20</td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
