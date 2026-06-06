<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Inventory Item</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('inventory.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="item_name" placeholder="Item Name" class="rounded-lg" required>
                    <input type="text" name="category" placeholder="Category (e.g., Electronics)" class="rounded-lg" required>
                    <input type="number" name="quantity" placeholder="Quantity" class="rounded-lg" required>
                    <input type="text" name="location" placeholder="Location" class="rounded-lg" required>
                    <select name="status" class="rounded-lg col-span-2">
                        <option value="Good">Good</option>
                        <option value="Damaged">Damaged</option>
                        <option value="In Use">In Use</option>
                    </select>
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Save Item</button>
            </form>
        </div>
    </div>
</x-app-layout>
