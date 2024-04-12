<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Simplicity is an acquired taste. - Katharine Gerould -->
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Create Entry for {{$patient->name}}</h1>
        </div>
        <div>
            <form action={{route('entries.store')}} method="POST" class="w-full" enctype="multipart/form-data">
                <input type="hidden" name="patient_id" value="{{$patient->id}}" />
                @csrf
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Name</span>
                    </div>
                    <input type="text" name="name" value="{{$patient->name}}" disabled class="input input-disabled input-bordered w-full" required />
                </label enctypemultipart/form-data>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Personnummer</span>
                    </div>
                    <input type="text" name="personnummer" value="{{$patient->personnummer}}" disabled class="input-disabled input input-bordered w-full" required />
                </label>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Title</span>
                    </div>
                    <input type="text" name="title" placeholder="Entry Title" autofocus class="input input-bordered w-full" required />
                </label>
                <label class="form-control">
                    <div class="label">
                        <span class="label-text">Entry Description</span>
                    </div>
                    <textarea class="textarea textarea-bordered h-48" placeholder="Description" name="description" required></textarea>
                </label>
                <input type="file" name="files[]" class="mt-4 file-input file-input-bordered file-input-md w-full" multiple />
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button class="btn btn-wide btn-primary">Create</button>
                    <a class="btn btn-ghost" href={{route('patients.index')}}>Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
