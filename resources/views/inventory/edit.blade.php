<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Item</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('inventory.update', $item->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="item_name" value="{{ $item->item_name }}" class="rounded-lg" required>
                    <input type="number" name="quantity" value="{{ $item->quantity }}" class="rounded-lg" required>
                </div>
                <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Update Item</button>
            </form>
        </div>
    </div>
</x-app-layout>
