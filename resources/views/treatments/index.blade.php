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
        <h1 id="heading" class="text-2xl mb-6">All Treatments</h1>
        <div class="flex justify-between items-center">
            <div class="flex gap-4 justify-start items-center text-sm">
                <!-- Search form -->
                <form method="GET" action="{{ route('treatments.index') }}" class="m-0 flex items-center">
                    <label class="flex items-center w-full justify-between rounded-sm">
                        <input id="search" type="text" name="search"
                                                       class="text-sm rounded-l-sm w-48 grow border border-slate-300 focus:border-slate-300 focus:ring-0" placeholder="Query..." />
                        <button type="submit" class="border-l-0 border py-2 border-slate-300 bg-white hover:bg-gray-100 text-blue-950 px-2 rounded-r-sm">Search</button>
                    </label>
                </form>
            </div>
            <a href="{{ route('treatments.create') }}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New treatment</a>
        </div>

        <div class="overflow-x-auto mt-6 bg-slate-50 border border-slate-300 rounded-sm">
            <table class="table text-base w-full">
                <!-- head -->
                <thead class="text-sm border-b bg-slate-200 text-slate-600
                text-left">
                    <tr>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Description</th>
                        <th class="py-2 px-4">Last Updated</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $treatment)
                        <tr class="border-t border-slate-200 hover:bg-slate-100">
                            <td class="py-4 px-4">{{$treatment->name}}</td>
                            <td class="py-4 px-4">{{$treatment->description}}</td>
                            <td class="py-4 px-4 format-date" data-date="{{$treatment->updated_at}}"></td>
                            <td class="py-4 px-4 text-right flex justify-end
                                items-center gap-2">
                                <a href={{ route('treatments.edit', $treatment) }} class="rounded-sm
                                hover:bg-gray-200 text-blue-950
                                hover:border-blue-950 bg-gray-50 border
                                border-slate-200 transition ease-in-out delay-50
                                py-2 px-8">Update</a>
                                <form class="m-0" action="{{ route('treatments.destroy', $treatment->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-sm
                                    hover:bg-gray-200 text-blue-950
                                    hover:border-blue-950 bg-gray-50 border
                                    border-slate-200 transition ease-in-out delay-50
                                    py-2 px-8">Delete</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$treatments->links('vendor.pagination.tailwind')}}
    </div>
</x-app-layout>
