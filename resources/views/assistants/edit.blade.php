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

                <form method="POST" action="{{ route('assistants.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Username -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                            :value="old('username', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <small>Leave password blank to keep current password</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" class="rounded block mt-1 w-full">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="assistant" {{ $user->role == 'assistant' ? 'selected' : '' }}>Assistant
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                    <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-8">
                        <button type="submit"
                            class="drop-shadow bg-blue-950
            hover:bg-blue-900 text-white rounded-sm py-2 px-8">Update</button>
                        <a class="border border-blue-950 bg-white hover:bg-slate-200
            text-blue-950 rounded-sm py-2 px-8"
                            href="{{ route('assistants.index') }}">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
