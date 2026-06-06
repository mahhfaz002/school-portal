<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📝 Online Examinations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Available Quizzes</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- @foreach($quizzes as $quiz) --}}
                    <div class="border p-4 rounded-lg">
                        <h4 class="font-bold">Mathematics Test</h4>
                        <p class="text-sm text-gray-600">Duration: 60 mins</p>
                        <button class="mt-2 bg-purple-600 text-white px-3 py-1 rounded w-full">Start Quiz</button>
                    </div>
                    {{-- @endforeach --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
