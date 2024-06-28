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
        <div class="flex justify-between items-center mt-16">
            <h1 id="heading" class="text-2xl">All Patients</h1>
            <div class="flex gap-4 justify-end items-center">
                <!-- Search form -->
                <form method="GET" action="{{ route('patients.index') }}" class="m-0">
                    <label class="flex items-center w-full justify-between rounded-sm">
                        <input id="search" type="text" name="search" class="rounded-l-sm w-48 grow border border-slate-300 focus:border-slate-300 focus:ring-0" placeholder="Query..." />
                        <button type="submit" class="border-l-0 border py-2 border-slate-300 bg-white hover:bg-gray-100 text-blue-950 px-2 rounded-r-sm">Search</button>
                    </label>
                </form>

                <a href="{{ route('patients.create') }}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Patient</a>
            </div>
        </div>
        <div class="overflow-x-auto mt-4 bg-slate-50 border border-slate-300 rounded-sm">
            <table class="table text-base">
                <!-- head -->
                <thead class="text-sm border-b bg-slate-200 text-slate-600">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Last Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr class="border-t border-slate-200 hover:bg-slate-100">
                        <td>
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-medium">{{$patient->name}}</div>
                                    <div class="text-sm opacity-60">{{preg_replace("/-?\d{4}$/", "-XXXX",$patient->personnummer)}}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{$patient->email}}</td>
                        <td>{{$patient->phone}}</td>
                        <td class="format-date" data-date="{{$patient->updated_at}}"></td>
                        <td class="text-right">
                            <a href="{{ route('patients.show', $patient) }}" class="rounded-sm hover:bg-gray-200 text-blue-950 hover:border-blue-950 bg-gray-50 border border-slate-200 transition ease-in-out delay-50 py-2 px-8 rounded-sm ">Open</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$patients->links('vendor.pagination.tailwind')}}
    </div>
</x-app-layout>