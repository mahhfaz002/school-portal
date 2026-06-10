<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🔔 Notifications</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @forelse($items as $item)
                    <a href="{{ $item['url'] }}" class="flex items-start gap-4 px-6 py-4 border-b last:border-0 hover:bg-gray-50 transition">
                        <span class="text-2xl">{{ $item['icon'] }}</span>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">{{ $item['title'] }}</p>
                            @if(!empty($item['detail']))<p class="text-sm text-gray-600 whitespace-pre-line">{{ $item['detail'] }}</p>@endif
                        </div>
                        <span class="text-[11px] text-gray-400 whitespace-nowrap">{{ $item['time']?->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="px-6 py-16 text-center text-gray-400 italic">You're all caught up — no notifications.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
