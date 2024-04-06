<x-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
        <div class="flex justify-start items-center gap-6 mb-6">
            <a class="btn btn-ghost" href={{route('patients.index')}}>Back</a>
            <h1 class="text-2xl">{{$patient->name}}</h1>
        </div>
        <div>
            Entries
        </div>
    </div>
</x-layout>
