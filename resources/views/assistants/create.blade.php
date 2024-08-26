<x-app-layout>
    <div class="container mx-auto mt-16">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
    <h1 class="text-2xl mb-4">Create new Assistant</h1>
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


            <form action="{{ route('assistants.store') }}" class="w-full" method="POST">
    @csrf
    <div class="mb-4 w-full">
        <label for="name" class="block text-slate-600 mb-2">Name:</label>
        <input type="text" name="name" id="name" class="w-full rounded" required>
    </div>
    <div class="mb-4 w-full">
        <label for="email" class="block text-slate-600 mb-2">Email:</label>
        <input type="email" name="email" id="email" class="w-full rounded" required>
    </div>
    <div class="mb-4 w-full">
        <label for="password" class="block text-slate-600 mb-2">Password:</label>
        <input type="password" name="password" id="password" class="w-full
        rounded" required>
    </div>
    <div class="mb-4 w-full">
        <label for="role" class="block text-slate-600 mb-2">Role:</label>
        <select name="role" id="role" class="w-full rounded" required>
            <option value="assistant">Assistant</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-8">
        <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Create</button>
        <a class="border border-blue-950 bg-white hover:bg-slate-200
        text-blue-950 rounded-sm py-2 px-8" href="{{
        route('assistants.index') }}">Cancel</a>
    </div>
</form>

            </div>
        </div>
    </div>
</x-app-layout>
