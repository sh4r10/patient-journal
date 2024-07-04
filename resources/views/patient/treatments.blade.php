<!-- resources/views/patient/show.blade.php -->
@vite(['resources/js/display-dates.js'])

<x-app-layout>
    <div class="container mx-auto max-w-screen-lg mt-4">
        <x-patient-nav :patient="$patient" active="treatments" />
        <div class="w-full mt-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl">Treatments</h1>
                <a href="{{route('entries.create', $patient)}}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Entry</a>
            </div>
        </div>

        <div class="flex flex-col items-center text-base bg-white p-6 mt-4 rounded-lg shadow-sm hover:shadow-md transition ease-in-out delay-50">
            <h2 class="text-xl font-bold mb-4">Treatments</h2>
            <ul class="list-disc list-inside">
                @forelse ($treatments as $treatment)
                <li>{{ $treatment->name }}</li>
                @empty
                <li>No treatments assigned.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>