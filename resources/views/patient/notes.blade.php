@vite(['resources/js/display-dates.js'])

<x-app-layout>
    <div class="container mx-auto max-w-screen-lg mt-8">
        <x-patient-nav :patient="$patient" active="notes" />

        <!-- Page Header -->
        <div class="bg-blue-50 p-4 rounded-lg shadow-md mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold text-gray-800">Notes for {{ $patient->name }}</h1>
               
            </div>
        </div>

        <!-- Notes Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-700">Notes</h2>

            <!-- Note Submission Form -->
            <form method="POST" action="{{ route('patients.notes.store', $patient->id) }}" class="mb-6">
                @csrf
                <textarea name="content" class="w-full border border-gray-300 rounded-lg p-3 mb-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a new note..." required></textarea>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">Add Note</button>
            </form>

            <!-- Display Notes -->
            @if($notes->isEmpty())
                <p class="text-gray-500">No notes available.</p>
            @else
                <ul class="list-disc pl-5 space-y-4">
                    @foreach($notes as $note)
                        <li class="flex flex-col border-b border-gray-200 pb-4">
                            <p class="text-gray-800 text-lg font-semibold">{{ $note->content }}</p>
                            <span class="text-sm text-gray-500 mt-1">({{ $note->created_at->format('d M Y') }})</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
