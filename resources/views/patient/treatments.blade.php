@vite(['resources/js/display-dates.js'])

<x-app-layout>
    <div class="container mx-auto max-w-screen-lg mt-8">
        <!-- Navigation and Header -->
        <x-patient-nav :patient="$patient" active="treatments" />
        
        <div class="w-full mt-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800">Treatments</h1>
            </div>
        </div>

        <!-- Treatment Management Section -->
        <div class="bg-white p-8 mt-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
           
            <!-- Display Success and Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form to Assign a New Treatment -->
            
                <form method="POST" action="{{ route('patients.assignTreatment', $patient) }}" class="mb-8">
                    @csrf
                    <div class="mb-4">
                        <label for="treatment" class="block text-gray-600 font-medium mb-2">Assign New Treatment</label>
                        <select name="treatment_id" id="treatment" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @foreach($allTreatments as $treatment)
                                <option value="{{ $treatment->id }}" {{ $patient->treatments->contains($treatment->id) ? 'disabled' : '' }}>
                                    {{ $treatment->name }} {{ $patient->treatments->contains($treatment->id) ? '(Already assigned)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors duration-200">
                        Assign Treatment
                    </button>
                </form>
            

            <!-- Display Assigned Treatments -->
            <div class="mt-6">
                <h1 class="text-xl font-medium text-gray-700 mb-4"> <b>Assigned Treatments</b></h1>
                <ul class="list-disc list-inside space-y-4">
                    @forelse ($treatments as $treatment)
                        <li class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                            <span class="text-blue-900">{{ $treatment->name }}</span>
                            
                                <!-- Unassign Button -->
                                <form method="POST" action="{{ route('patients.unassignTreatment', [$patient, $treatment]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Unassign
                                    </button>
                                </form>
                            
                        </li>
                    @empty
                        <li class="text-gray-500">No treatments assigned.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
