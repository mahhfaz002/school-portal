<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Student Admission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg sm:rounded-lg border-t-4 border-blue-500">

                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Full Name</label>
                        <input type="text" name="full_name" class="w-full border-gray-300 rounded mt-1" placeholder="e.g. Adebayo Chukwuma" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Admission Number</label>
                        <input type="text" name="admission_number" class="w-full border-gray-300 rounded mt-1" placeholder="SCH/2026/001" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Student Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full border-gray-300 rounded mt-1 p-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1 italic">Best for ID Card: Square aspect ratio, under 2MB.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Class / Arm</label>
                        <select name="class_arm" class="w-full border-gray-300 rounded mt-1">
                            <option value="JSS 1A">JSS 1A</option>
                            <option value="JSS 1B">JSS 1B</option>
                            <option value="SSS 1 Science">SSS 1 Science</option>
                            <option value="SSS 1 Arts">SSS 1 Arts</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Parent Phone Number</label>
                        <input type="text" name="parent_phone" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-gray-700">Initial Fees Balance (₦)</label>
                        <input type="number" name="fees_balance" value="0" class="w-full border-gray-300 rounded mt-1" required>
                    </div>

                    <div class="pt-4 border-t">
                        <button type="submit"
                                style="background-color: #059669; color: white; padding: 12px 30px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer; width: 100%; font-size: 16px;">
                            ADMIT STUDENT & SAVE TO DATABASE
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
