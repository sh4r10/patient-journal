<!-- resources/views/treatments/edit.blade.php -->
<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Simplicity is an acquired taste. - Katharine Gerould -->
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Edit Treatment</h1>
        </div>
        <div>
            <form action="{{ route('treatments.update', $treatment) }}" method="POST" class="w-full">
                @csrf
                @method('PUT')
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" value="{{ $treatment->name }}" class="input input-bordered w-full" />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Description</span>
                        </div>
                        <textarea name="description" class="input input-bordered w-full">{{ $treatment->description }}</textarea>
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button class="btn btn-wide btn-primary">Update</button>
                    <a class="btn btn-ghost" href="{{ route('treatments.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
