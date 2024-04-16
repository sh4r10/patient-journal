<div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius      loooool somtimes you gotta jump when you feel not to ;) -->
    <h1>Edit Entry</h1>
</div>
<x-app-layout>
   
            <h1 class="text-2xl">Edit Entry for {{$journalEntry->journalEntryPatient->name ?? 'Unknown'}}</h1>
        </div>
        <div>
            <form action="{{ route('entries.update', ['journalEntry' => $journalEntry->id]) }}" method="POST" class="w-full" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Title</span>
                    </div>
                    <input type="text" name="title" value="{{ $journalEntry->title }}" class="input input-bordered w-full" required />
                </label>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Description</span>
                    </div>
                    <textarea class="textarea textarea-bordered h-48" name="description" required>{{ $journalEntry->description }}</textarea>
                </label>
                <input type="file" name="files[]" class="mt-4 file-input file-input-bordered file-input-md w-full" multiple />
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button class="btn btn-wide btn-primary">Update Entry</button>
                    <a class="btn btn-ghost" href="{{ route('patients.show', ['patient' => $journalEntry->patient_id]) }}">Cancel</a>
                </div>
            </form>

           
        </div>
    </div>
</x-app-layout>
