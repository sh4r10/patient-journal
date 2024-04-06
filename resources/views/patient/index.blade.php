<x-layout>
    <div class="mt-12 max-w-screen-lg m-auto">
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl mb-6">All Patients</h1>
            <a href="{{route('patients.create')}}" class="text-white py-2 px-4 rounded btn-wide btn bg-emerald-700 hover:bg-emerald-800 transition ease-in-out delay-50">
                Create New
            </a>
        </div>
        @foreach($patients as $patient)
        <a class="text-blue-800 underline" href={{route('patients.show', $patient)}}>{{$patient->name}}</a>
        @endforeach
    </div>
</x-layout>
