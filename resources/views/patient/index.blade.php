<x-layout>
    <div class="mt-12 max-w-screen-lg m-auto">
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl">All Patients</h1>
            <a href={{route('patients.create')}} class="btn btn-secondary">New Patient</a>
        </div>
        <div class="flex flex-col mt-4 gap-2">
            @foreach($patients as $patient)
            <a class="w-full bg-white py-4 px-6 rounded shadow-sm hover:shadow-md transition ease-in-out delay-50" href={{route('patients.show', $patient)}}>{{$patient->name}}</a>
            @endforeach
        </div>
    </div>
</x-layout>
