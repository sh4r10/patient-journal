<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
        <div class="flex justify-between items-center">
            <div class="flex justify-start items-center gap-6 mb-6">
                <a class="btn btn-ghost" href={{route('patients.index')}}>Back</a>
                <h1 class="text-2xl">{{$patient->name}}</h1>
            </div>
            <div class="flex gap-2">
                <a class="btn btn-outline btn-warning" href={{route('patients.edit', $patient)}}>Update</a>
                <form action={{route('patients.destroy', $patient)}} method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline btn-error">Delete</button>
                </form>
            </div>
        </div>
        <div>
            Entries
        </div>
    </div>
</x-app-layout>
