<x-app-layout>
    <div class="mt-16 max-w-screen-md m-auto">
        <!-- Simplicity is an acquired taste. - Katharine Gerould  I keep teeling u but u r stubborn   -->
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Create Entry for {{$patient->name}}</h1>
        </div>
        <div>
            <form action="{{route('entries.store')}}" method="POST" class="w-full" enctype="multipart/form-data">
                <input type="hidden" name="patient_id" value="{{$patient->id}}" />
                @csrf
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Title</span>
                    </div>
                    <input type="text" name="title" placeholder="Entry Title"
                    autofocus class="rounded-sm mb-4 mt-2 input input-bordered w-full" required />
                </label>
                <label class="form-control">
                    <div class="label">
                        <span class="label-text">Entry Description</span>
                    </div>
                    <textarea class="w-full rounded-sm mb-4 mt-2 textarea textarea-bordered h-48" placeholder="Description" name="description" required></textarea>
                </label>
                <label>Attach Files</label>
                <input type="file" name="files[]" class="mt-4 file-input file-input-bordered file-input-md w-full" multiple />
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Create</button>
                    <a class="border border-blue-950 bg-white hover:bg-gray-200
                    text-blue-950 rounded-sm py-2 px-8" href="{{
                    route('patients.show', $patient) }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
