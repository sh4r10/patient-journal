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
                <div class="flex gap-6 mb-4">
                    <label class="form-control w-full">
                        <div class="label text-slate-600 mb-2">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" value="{{
                        $treatment->name }}" class="w-full rounded" />
                    </label>
                </div>
                <div class="flex gap-6 mb-4">
                    <label class="form-control w-full">
                        <div class="label text-slate-600 mb-2">
                            <span class="label-text">Description</span>
                        </div>
                        <textarea rows=10 name="description" class="w-full
                        rounded">{{ $treatment->description }}</textarea>
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button type="submit" class="drop-shadow bg-blue-950
                    hover:bg-blue-900 text-white rounded-sm py-2 px-8">Update</button>
                    <a class="border border-blue-950 bg-white hover:bg-slate-200
                    text-blue-950 rounded-sm py-2 px-8" href="{{ route('treatments.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
