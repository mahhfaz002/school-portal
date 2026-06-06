<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Collect Fees: {{ $student->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <p class="mb-4 text-red-600 font-bold">Current Balance: ₦{{ number_format($student->fees_balance, 2) }}</p>

                <form action="{{ route('payments.store', $student->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-bold">Amount Paid (₦)</label>
                        <input type="number" name="amount" class="w-full border-gray-300 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Payment Method</label>
                        <select name="payment_method" class="w-full border-gray-300 rounded">
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="POS">POS</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Description/Remark</label>
                        <input type="text" name="description" placeholder="e.g. 2nd Term Part Payment" class="w-full border-gray-300 rounded">
                    </div>

                    <button type="submit" style="background-color: #059669; color: white; padding: 10px; width: 100%; border-radius: 5px; font-weight: bold;">
                        CONFIRM PAYMENT
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
