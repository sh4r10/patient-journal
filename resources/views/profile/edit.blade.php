@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
@endphp

<script>
    function humanFileSize(size) {
        var i = size == 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024));
        return +((size / Math.pow(1024, i)).toFixed(2)) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }

    function showDeleteConfirmation(e) {
        e.preventDefault();
        e.stopPropagation();
        const delete_confirmation = document.getElementById("delete_confirmation");
        delete_confirmation.classList.remove("hidden");
        delete_confirmation.classList.add("block");
        document.body.classList.add("overflow-hidden");
    }

    function closeDeleteConfirmation(e) {
        e.preventDefault();
        e.stopPropagation();
        const delete_confirmation = document.getElementById("delete_confirmation");
        delete_confirmation.classList.add("hidden");
        delete_confirmation.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
    }

    function submitForm(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = document.getElementById("delete-form");
        form.submit();
    }

    window.onload = function() {
        const span = document.getElementById("humanize-bytes");
        const bytes = parseInt(span.innerHTML);
        span.innerHTML = humanFileSize(bytes);
    }
</script>

<!-- Delete confirmation Modal -->
<div onclick="closeDeleteConfirmation(event)" id="delete_confirmation"
    class="hidden fixed z-30 left-0 top-0 w-full h-full
        bg-black bg-opacity-70 flex justify-center items-center">
    <div class="bg-white max-w-screen-sm w-full p-8 rounded" onclick="showDeleteConfirmation(event)">
        <h3 class="font-medium text-slate-700 text-lg">Confirm password
            to permanently delete data</h3>
        <form action="{{ route('data.delete') }}" method="post" id="delete-form">
            @csrf
            @method('DELETE')
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" class="text-md
                        font-semibold mb-2"
                    :value="__('Password')" />

                <x-text-input id="password" class="block py-3 mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex gap-2 flex-wrap flex-row-reverse justify-start items-center mt-4 w-full">
                <button onclick="submitForm(event)"
                    class="drop-shadow bg-red-700
                                hover:bg-red-800 text-white rounded-sm py-2
                                px-8">Delete</button>
                <button
                    class="bg-slate-100
                                hover:bg-slate-200 text-slate-900 rounded-sm py-2
                                px-8"
                    onClick="closeDeleteConfirmation(event)">Cancel</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</div>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if ($isAdmin)
                <div
                    class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex
            justify-between items-center flex-wrap">
                    <p>Total size of data that has been deleted for 30 days:
                        <span id="humanize-bytes" class="ml-2 text-lg font-bold">{{ $deleted_data_size }}<span>
                    </p>
                    <button onclick="showDeleteConfirmation(event)"
                        class="bg-red-50
                    hover:bg-red-100 border border-red-200 text-red-900 rounded-sm py-2
                    px-8">Delete</button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
