<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📢 Announcements</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            @if($canManage)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-700 mb-4">Post a new announcement</h3>
                    <form method="POST" action="{{ route('announcements.store') }}" class="space-y-3">
                        @csrf
                        <input name="title" placeholder="Title" required class="w-full rounded-lg border-gray-300">
                        <textarea name="body" placeholder="Message..." rows="3" required class="w-full rounded-lg border-gray-300"></textarea>
                        <div class="flex items-center gap-3">
                            <select name="audience" class="rounded-lg border-gray-300">
                                <option value="all">Everyone</option>
                                <option value="staff">All Staff</option>
                                <option value="teacher">Teachers</option>
                                <option value="student">Students</option>
                                <option value="accountant">Accountants</option>
                                <option value="principal">Principals</option>
                            </select>
                            <button class="text-white px-5 py-2 rounded-lg font-bold" style="background: var(--brand)">Post</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="space-y-4">
                @forelse($announcements as $a)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $a->title }}</h4>
                                <p class="text-xs text-gray-400">
                                    {{ $a->author->name ?? 'System' }} • {{ $a->created_at->diffForHumans() }}
                                    <span class="ml-2 px-2 py-0.5 bg-gray-100 rounded-full uppercase">{{ $a->audience }}</span>
                                </p>
                            </div>
                            @if($canManage)
                                <form method="POST" action="{{ route('announcements.destroy', $a) }}" onsubmit="return confirm('Delete this announcement?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-xs font-bold hover:underline">Delete</button>
                                </form>
                            @endif
                        </div>
                        <p class="mt-2 text-gray-600 whitespace-pre-line">{{ $a->body }}</p>
                    </div>
                @empty
                    <p class="text-center text-gray-400 italic py-8">No announcements yet.</p>
                @endforelse
            </div>

            {{ $announcements->links() }}
        </div>
    </div>
</x-app-layout>
