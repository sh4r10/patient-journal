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
                <textarea name="content" rows=5
                    class="w-full border border-slate-300 rounded-lg p-3 mb-4 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Write a new note..." required></textarea>
                <div class="w-full flex justify-end items-center">
                    <button type="submit"
                        class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Add
                        Note</button>
                </div>
            </form>

            <!-- Display Notes -->
            <h2 class="text-lg font-medium text-slate-700 my-4">Previous Notes</h2>
            @if ($notes->isEmpty())
                <p class="text-slate-500">No notes available.</p>
            @else
                <ul class="list-none">
                    @foreach ($notes as $note)
                        <li
                            class="bg-white hover:bg-yellow-50 rounded px-6
                        py-4 mb-2 drop-shadow-sm flex">
                            <div class="flex flex-col flex-grow justify-between items-start">
                                <p class="text-slate-800 text-lg">{{ $note->content }}</p>
                                <span
                                    class="mt-2 text-sm font-semibold
                                text-slate-500">{{ $note->created_at->format('d M Y H:m') }}</span>
                            </div>
                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('patients.notes.destroy', $note->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this note?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-200
                        text-red-500 rounded-full hover:bg-red-600
                        hover:text-white p-1 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent multiple form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(event) {
                const submitButton = event.target.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled =
                        true; // Disable the submit button to prevent multiple clicks
                    setTimeout(() => submitButton.disabled = false,
                        1000); // Re-enable after 1 second
                }
            });
        });

        // Handle form reset on navigation
        window.addEventListener('popstate', function() {
            console.log('Navigated back');
            document.querySelectorAll('form').forEach(form => form.reset());
        });

        // Debug form submissions and button clicks
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                console.log('Form submitted');
            });
        });

        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function() {
                console.log('Submit button clicked');
            });
        });
    });
</script>
