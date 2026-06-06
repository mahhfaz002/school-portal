<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🖥️ ICT System Console: <span class="text-purple-600">Administrator</span>
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('register') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg font-bold hover:bg-gray-900 transition text-sm">
                    ➕ Create New User
                </a>
                <a href="#" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-purple-700 transition text-sm">
                    🔄 System Backup
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total System Users</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $totalUsers }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Sessions</p>
                    <h3 class="text-3xl font-black text-purple-600">{{ $activeSessions }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">System Health</p>
                    <span class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">All Systems Operational</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">System Management</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" class="p-4 bg-gray-50 rounded-lg hover:bg-purple-50 border border-transparent hover:border-purple-200 transition">
                            <span class="block font-bold text-purple-700">Manage Users</span>
                            <span class="text-xs text-gray-500">Edit roles, reset passwords</span>
                        </a>
                        <a href="#" class="p-4 bg-gray-50 rounded-lg hover:bg-purple-50 border border-transparent hover:border-purple-200 transition">
                            <span class="block font-bold text-purple-700">Database Sync</span>
                            <span class="text-xs text-gray-500">Force sync student data</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="font-bold text-gray-700">Recent System Activity</h3>
                    </div>
                    <div class="p-6 space-y-4 text-sm text-gray-600">
                        @forelse($latestSystemLogs as $log)
                        <div class="border-b pb-2 last:border-0">
                            <span class="font-bold text-purple-700">[{{ $log->created_at->format('H:i:s') }}]</span>
                            {{ $log->description }}
                        </div>
                        @empty
                        <p class="text-gray-400 italic">No activity logs recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
