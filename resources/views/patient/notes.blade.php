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
                <ul class="list-none space-y-4">
                    @foreach($notes as $note)
                        <li class="flex items-start justify-between p-4 border border-gray-200 rounded-lg shadow-md hover:bg-gray-50 transition-colors duration-300">
                            <div class="flex-1">
                                <p class="text-gray-800 text-lg font-semibold">{{ $note->content }}</p>
                                <span class="text-sm text-gray-500">{{ $note->created_at->format('d M Y') }}</span>
                            </div>
                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('patients.notes.destroy', $note->id) }}" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm ml-4">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Prevent multiple form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (event) {
                const submitButton = event.target.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;  // Disable the submit button to prevent multiple clicks
                    setTimeout(() => submitButton.disabled = false, 1000);  // Re-enable after 1 second
                }
            });
        });

        // Handle form reset on navigation
        window.addEventListener('popstate', function () {
            console.log('Navigated back');
            document.querySelectorAll('form').forEach(form => form.reset());
        });

        // Debug form submissions and button clicks
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                console.log('Form submitted');
            });
        });

        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function () {
                console.log('Submit button clicked');
            });
        });
    });
</script>
