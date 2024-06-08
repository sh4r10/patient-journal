<!-- resources/views/treatments/show.blade.php -->
@vite(['resources/js/display-dates.js'])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Treatment Details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-12">
        <div class="flex justify-start mb-4">
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Back to Treatments</a>
        </div>

        <div class="flex justify-center">
            <div class="md:w-1/3 w-full">
                <div class="flex flex-col items-center text-base bg-white p-12 rounded-lg shadow-sm hover:shadow-md transition ease-in-out delay-50">
                    <img class="rounded-full w-32" src="https://ui-avatars.com/api/?name={{$treatment->name}}&size=128&background=58b177&rounded=true&format=svg" alt="profile-picture">
                    <h1 class="text-2xl font-bold">{{ $treatment->name }}</h1>
                    <p class="text-gray-600">{{ $treatment->description }}</p>
                    <div class="dropdown dropdown-bottom dropdown-end dropdown-hover">
                        <div tabindex="0" role="button" class="btn btn-wide btn-neutral">Manage Treatment</div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li>
                                <a href={{ route('treatments.edit', $treatment) }}>Update</a>
                            </li>
                            <li>
                                <form action="{{ route('treatments.destroy', $treatment->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
