<x-app-layout>
    <div class="container mx-auto max-w-screen-lg mt-4">
        <!-- Navigation and Header -->
        <x-patient-nav :patient="$patient" active="treatments" />

        <!-- Treatment Management Section -->
        <div class="bg-white mt-8">
            <div class="flex justify-start items-center">
                <h1 class="text-2xl font-medium">Treatments</h1>
            </div>

            <!-- Form to Assign a New Treatment -->
            <h2 for="treatment" class="text-lg font-medium text-gray-700 my-4">Add New Treatment</h2>
            @if(Auth::check() && Auth::user()->isAdmin())
                <form method="POST" action="{{ route('patients.assignTreatment',
                $patient) }}" class="mb-8 flex flex-wrap gap-2">
                    @csrf
                    <div class="flex-grow">
                        <select name="treatment_id" id="treatment" class="w-full
                        rounded-sm min-w-96 p-3
                        text-gray-700 placeholder-gray-400 outline-none
                        border-slate-700 focus:border-slate-700 border focus:outline-none focus:ring-0" required>
                            @foreach($allTreatments as $treatment)
                                <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">
                        Add Treatment
                    </button>
                </form>
            @endif

            <!-- Display Assigned Treatments -->
            <div class="mt-6">
                <h2 class="text-lg font-medium text-gray-700 mt-8 mb-4">Assigned Treatments</h1>
                <ul class="list-none">
                    @forelse ($treatments as $treatment)
                        <li class="bg-blue-50 hover:bg-blue-100 drop-shadow-sm
                        mb-2 rounded px-6 py-4 flex justify-between items-center">
                            <span class="text-slate-900 font-medium">{{ $treatment->name }}</span>
                            @if(Auth::check() && Auth::user()->isAdmin())
                                <!-- Unassign Button -->
                                <form method="POST" action="{{ route('patients.unassignTreatment', [$patient, $treatment]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </li>
                    @empty
                        <li class="text-gray-500">No treatments assigned.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
