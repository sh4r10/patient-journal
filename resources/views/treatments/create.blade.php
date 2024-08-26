<!-- resources/views/treatments/create.blade.php -->
<x-app-layout>
    <div class="mt-16 max-w-screen-md m-auto">
    <h1 class="text-2xl mb-6">Create New Treatment</h1>
        <form action="{{ route('treatments.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-slate-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-700 text-sm font-bold mb-2">Description:</label>
                <textarea name="description" rows=8 id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Create</button>
                <a class="border border-blue-950 bg-white hover:bg-slate-200
                text-blue-950 rounded-sm py-2 px-8" href="{{ route('assistants.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
