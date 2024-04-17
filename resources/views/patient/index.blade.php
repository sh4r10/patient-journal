<x-app-layout>
    <div class="mt-12 max-w-screen-lg mx-auto">
                                                    <!-- Search form -->
        <div class="flex justify-center mb-6">
            <form method="GET" action="{{ route('patients.index') }}" class="w-full max-w-lg">
                <div class="flex items-center border-2 border-gray-300 p-2 rounded-lg shadow-sm">
                    <input type="text" name="search" class="appearance-none bg-white-100 border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" placeholder="Search by Name, SSN, Email, or Phone" aria-label="Search">
                    <button type="submit" class="flex-shrink-0 bg-blue-500 hover:bg-blue-700 border-blue-500 hover:border-blue-700 text-sm border-4 text-white py-1 px-2 rounded">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="flex justify-between items-center">
            <h1 class="text-2xl">All Patients</h1>
            <a href="{{ route('patients.create') }}" class="btn btn-primary">New Patient</a>
        </div>

                                  <!-- Patients listing with headings -->
        <div class="mt-4">
                                          <!-- Headings -->
            <div class="grid grid-cols-5 gap-4 mb-2 font-medium text-green-800">
                <div>Name</div>
                <div>SSN</div>
                <div>Email</div>
                <div>Phone</div>
                <div>Details</div>
            </div>
            @foreach($patients as $patient)
            <div class="grid grid-cols-5 gap-4 bg-white py-4 px-6 rounded shadow-sm hover:shadow-md transition ease-in-out delay-50 mb-2">
                <div>{{ $patient->name }}</div>
                <div>{{ $patient->personnummer }}</div>
                <div>{{ $patient->email }}</div>
                <div>{{ $patient->phone }}</div>
                <a href="{{ route('patients.show', $patient) }}" class="text-green-700 hover:text-blue-600 transition ease-in-out duration-150 mx-3 my-2 md:mx-4 md:my-3 border border-gray-300 hover:border-blue-400 rounded px-2 py-1 shadow-sm hover:shadow bg-gray-100 hover:bg-gray-200">View Details</a>







            </div>
            @endforeach
        </div>

        {{$patients->links('vendor.pagination.tailwind')}}
    </div>
</x-app-layout>
