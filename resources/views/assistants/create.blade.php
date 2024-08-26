<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Create New User') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-12">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
            
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


            <form action="{{ route('assistants.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-slate-700">Name:</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-4">
        <label for="email" class="block text-slate-700">Email:</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-4">
        <label for="password" class="block text-slate-700">Password:</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="mb-4">
        <label for="role" class="block text-slate-700">Role:</label>
        <select name="role" id="role" class="form-control" required>
            <option value="assistant">Assistant</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">Create User</button>
    </div>
</form>

            </div>
        </div>
    </div>
</x-app-layout>
