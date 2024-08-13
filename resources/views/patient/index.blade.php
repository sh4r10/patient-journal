<!-- resources/views/patient/index.blade.php -->
@vite(['resources/js/display-dates.js'])
<script>
    window.addEventListener("load", () => {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if (urlParams.has("search")) {
            document.getElementById("heading").innerHTML = "Search Results";
            document.getElementById("search").value = urlParams.get("search");
        }
        humanizeDates();
    });
</script>
<x-app-layout>
    <div class="mt-16 max-w-screen-xl mx-auto">
        <h1 id="heading" class="text-2xl mb-6">All Patients</h1>
        <div class="flex justify-between items-center">
            <div class="flex gap-4 justify-start items-center text-sm">
                <!-- Search form -->
                <form method="GET" action="{{ route('patients.index') }}" class="m-0 flex items-center">
                    <label class="flex items-center w-full justify-between rounded-sm">
                        <input id="search" type="text" name="search"
                                                       class="text-sm rounded-l-sm w-48 grow border border-slate-300 focus:border-slate-300 focus:ring-0" placeholder="Query..." />
                        <button type="submit" class="border-l-0 border py-2 border-slate-300 bg-white hover:bg-gray-100 text-blue-950 px-2 rounded-r-sm">Search</button>
                    </label>
                </form>
                <button type="button" class="px-6 py-2 bg-slate-200
                text-slate-950 hover:bg-slate-300" onclick="document.getElementById('filter-modal').showModal()">Filter</button>
            </div>
            <a href="{{ route('patients.create') }}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Patient</a>
        </div>

        <!-- Filter Modal -->
        <dialog id="filter-modal" class="modal">
        <form method="GET" action="{{ route('patients.index') }}" class="modal-box">
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
        </form>
        </dialog>

        <div class="overflow-x-auto mt-6 bg-slate-50 border border-slate-300 rounded-sm">
            <table class="table text-base w-full">
                <!-- head -->
                <thead class="text-sm border-b bg-slate-200 text-slate-600
                text-left">
                    <tr>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Phone</th>
                        <th class="py-2 px-4">Last Updated</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr class="border-t border-slate-200 hover:bg-slate-100">
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-medium">{{$patient->name}}</div>
                                        <div class="text-sm opacity-60">{{preg_replace("/-?\d{4}$/", "-XXXX",$patient->personnummer)}}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-4">{{$patient->email}}</td>
                            <td class="py-2 px-4">{{$patient->phone}}</td>
                            <td class="py-2 px-4 format-date" data-date="{{$patient->updated_at}}"></td>
                            <td class="py-2 px-4 text-right">
                                <a href="{{ route('patients.show', $patient) }}" class="rounded-sm hover:bg-gray-200 text-blue-950 hover:border-blue-950 bg-gray-50 border border-slate-200 transition ease-in-out delay-50 py-2 px-8 rounded-sm">Open</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$patients->links('vendor.pagination.tailwind')}}
    </div>
</x-app-layout>
