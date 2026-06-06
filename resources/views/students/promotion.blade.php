<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Promotion Tool</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow border-t-4 border-orange-500">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Bulk Class Promotion</h3>

                <form action="{{ route('students.promote') }}" method="POST" onsubmit="return confirm('Are you sure you want to move all students in this class?')">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase">Promote From:</label>
                            <select name="current_class" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Select Current Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class }}">{{ $class }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase">Move To:</label>
                            <input type="text" name="target_class" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. JSS 2A" required>
                        </div>
                    </div>

                    <div class="bg-orange-50 p-4 rounded-lg mb-6 border border-orange-200">
                        <p class="text-sm text-orange-700 font-medium italic">
                            💡 Tip: This will update the "Class/Arm" field for every student currently in the selected class.
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-black uppercase tracking-widest shadow-lg hover:bg-orange-700 transition">
                        🚀 Run Promotion Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
