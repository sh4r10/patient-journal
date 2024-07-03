@vite(['resources/js/display-dates.js'])
<script>
    window.onload = () => {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if (urlParams.has("search")) {
            document.getElementById("heading").innerHTML = "Search Results";
            document.getElementById("search").value = urlParams.get("search");
        }
    }
</script>
<x-app-layout>
    <div class="container mx-auto mt-12">
        <div class="flex justify-start mb-4">
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">Back to Patients</a>
        </div>

        <div class="mt-16 max-w-screen-lg mx-auto">
            <!-- Search form -->
            <div class="m-auto max-w-md">
                <form method="GET" action="{{ route('treatments.index') }}" class="flex flex-col gap-4">
                    <div class="flex items-center gap-2">
                        <label class="input input-bordered flex items-center w-full justify-between p-0 pl-2 py-6 rounded-l-full rounded-r-full">
                            <input id="search" type="text" name="search" class="grow border-none focus:ring-0" placeholder="Search..." />
                            <button type="submit" class="btn btn-secondary btn-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                                    <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </label>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('filter-modal').showModal()">Filter</button>
                    </div>

                    <!-- Filter Modal -->
                    <dialog id="filter-modal" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Select Treatments</h3>
                            <div class="flex flex-wrap gap-4 mb-4">
                                @foreach($allTreatments as $treatment)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="treatments[]" value="{{ $treatment->id }}" />
                                        <span>{{ $treatment->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="modal-action">
                                <button type="submit" class="btn btn-primary">Apply</button>
                                <button type="button" class="btn" onclick="document.getElementById('filter-modal').close()">Cancel</button>
                            </div>
                        </div>
                    </dialog>
                </form>
            </div>

            <div class="flex justify-between items-center mt-16">
                <h1 id="heading" class="text-2xl">Search Results</h1>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="table text-base">
                    <!-- head -->
                    <thead class="text-sm">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Last Updated</th>
                            <th>Treatments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        <tr class="hover group">
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->email }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td class="format-date" data-date="{{ $patient->updated_at }}"></td>
                            <td>
                                <ul>
                                    @foreach($patient->treatments as $treatment)
                                        <li>{{ $treatment->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <th>
                                <a href="{{ route('patients.show', $patient) }}" class="btn group-hover:btn-secondary btn-sm">Details</a>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $patients->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</x-app-layout>
