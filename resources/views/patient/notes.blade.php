@vite(['resources/js/display-dates.js'])

<x-app-layout>
    <div class="container mx-auto max-w-screen-lg mt-4">
        <x-patient-nav :patient="$patient" active="notes" />

       <!-- Notes Section -->
        <div class="bg-transparent mt-8">
            <h1 class="text-2xl font-medium mb-6">Notes for {{ $patient->name }}</h1>
            <!-- Note Submission Form -->
            <h2 class="text-lg font-medium text-slate-700 my-4">Create new note</h2>
            <form method="POST" action="{{ route('patients.notes.store', $patient->id) }}" class="mb-6">
                @csrf
                <textarea name="content" rows=5 class="w-full border border-slate-300 rounded-lg p-3 mb-4 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a new note..." required></textarea>
                <div class="w-full flex justify-end items-center">
                <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Add Note</button>
                </div>
            </form>

            <!-- Display Notes -->
            <h2 class="text-lg font-medium text-slate-700 my-4">Previous Notes</h2>
            @if($notes->isEmpty())
                <p class="text-slate-500">No notes available.</p>
            @else
                <ul class="list-none">
                    @foreach($notes as $note)
                        <li class="bg-white hover:bg-yellow-50 rounded px-6
                        py-4 mb-2 drop-shadow-sm">
                            <div class="flex flex-col justify-between items-start">
                                <p class="text-slate-800 text-lg">{{ $note->content }}</p>
                                <span class="mt-2 text-sm font-semibold
                                text-slate-500">{{ $note->created_at->format('d M
                                Y H:m') }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
