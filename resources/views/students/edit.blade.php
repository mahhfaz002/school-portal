<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Student: {{ $student->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg sm:rounded-lg">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-bold">Full Name</label>
                        <input type="text" name="full_name" value="{{ $student->full_name }}" class="w-full border-gray-300 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Admission Number</label>
                        <input type="text" name="admission_number" value="{{ $student->admission_number }}" class="w-full border-gray-300 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Class / Arm</label>
                        <select name="class_arm" class="w-full border-gray-300 rounded">
                            <option value="JSS 1A" {{ $student->class_arm == 'JSS 1A' ? 'selected' : '' }}>JSS 1A</option>
                            <option value="JSS 1B" {{ $student->class_arm == 'JSS 1B' ? 'selected' : '' }}>JSS 1B</option>
                            <option value="SSS 1 Science" {{ $student->class_arm == 'SSS 1 Science' ? 'selected' : '' }}>SSS 1 Science</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Parent Phone</label>
                        <input type="text" name="parent_phone" value="{{ $student->parent_phone }}" class="w-full border-gray-300 rounded" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold">Fees Balance (₦)</label>
                        <input type="number" name="fees_balance" value="{{ $student->fees_balance }}" class="w-full border-gray-300 rounded" required>
                    </div>

                    <button type="submit" style="background-color: #fbbf24; color: black; padding: 12px 30px; border-radius: 6px; font-weight: bold; width: 100%;">
                        UPDATE STUDENT RECORD
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
