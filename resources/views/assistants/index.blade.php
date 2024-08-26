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
        <div class="flex justify-between items-center">
            <h1 id="heading" class="text-2xl mb-6">All Assistants</h1>
            <a href="{{ route('assistants.create') }}" class="drop-shadow
            bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Add
            Assistant</a>
        </div>

        <div class="overflow-x-auto mt-6 bg-slate-50 border border-slate-300 rounded-sm">
            <table class="table text-base w-full">
                <!-- head -->
                <thead class="text-sm border-b bg-slate-200 text-slate-600
                text-left">
                    <tr>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Role</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t border-slate-200 hover:bg-slate-100">
                            <td class="py-2 px-4">{{$user->name}}</td>
                            <td class="py-2 px-4">{{$user->email}}</td>
                            <td class="py-2 px-4">{{ $user->role == "admin" ? 'admin' : 'assistant' }}</td>
                            <td class="py-2 px-4 text-right w-full flex
                                justify-end items-center gap-2">
                                   <a href="{{ route('assistants.edit', $user) }}"
                                   class="rounded-sm
                                hover:bg-slate-200 hover:border-slate-400 text-slate-900
                                bg-slate-50 border
                                border-slate-200 transition ease-in-out delay-50
                                py-2 px-8 text-sm">Update</a>
            <form class="m-0 font-sm" action="{{ route('assistants.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-sm
                                    hover:bg-red-100 hover:border-red-400 text-red-800
                                    bg-red-50 border
                                    border-red-200 transition ease-in-out delay-50
                                    py-2 px-8 text-sm" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$users->links('vendor.pagination.tailwind')}}
    </div>
</x-app-layout>
