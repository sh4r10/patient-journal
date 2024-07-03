<!-- resources/views/patient/show.blade.php -->
@vite(['resources/js/display-dates.js'])
<x-app-layout>

    @section('content')
    <div>
        <h1>Patient: {{ $patient->name }}</h1>
        <p>Email: {{ $patient->email }}</p>
        <p>Phone: {{ $patient->phone }}</p>

        <!-- Display a button/link for admins to view deleted entries -->
        @if(auth()->user()->isAdmin())
        <a href="{{ route('entries.deleted', $patient->id) }}" class="btn btn-warning">View Deleted Entries</a>
        @endif

        <ul>
            @foreach($entries as $entry)
            <li>{{ $entry->title }} - {{ $entry->description }}</li>
            @endforeach
        </ul>
        {{ $entries->links() }}
    </div>
    @endsection


    <div class="container mx-auto max-w-screen-lg mt-4">
        <div class="w-full flex flex-col items-center justify-center gap-4 text-blue-950">
            <ul class="w-fit flex bg-white drop-shadow-sm px-8 rounded-sm justify-center items-center">
                <li>
                    <div class="flex justify-start items-center mr-8 gap-2">
                        <img class="rounded-full w-8" src="https://ui-avatars.com/api/?name={{$patient->name}}&size=128&background=d4c4aa&color=1b2748&rounded=true&format=svg" alt="profile-picture">
                        <h1 class="text-lg font-medium">{{$patient->name}}</h1>
                    </div>
                </li>
                <li class="border-b-2 border-blue-950 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">Entries</li>
                <li class="border-b-2 border-white hover:border-gray-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">Notes</li>
                <li class="border-b-2 border-white hover:border-gray-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">Treatments</li>
                <li class="border-b-2 border-white hover:border-gray-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">Manage</li>
            </ul>
        </div>
        <!--
        <div class="md:w-1/3 w-full hidden">
            <div class="hidden flex flex-col items-center text-base bg-white p-12 rounded-lg shadow-sm hover:shadow-md transition ease-in-out delay-50">
                <img class="rounded-full w-32" src="https://ui-avatars.com/api/?name={{$patient->name}}&size=128&background=58b177&rounded=true&format=svg" alt="profile-picture">
                <h1 class="text-2xl font-bold">{{$patient->name}}</h1>
                <p class="text-gray-600">{{$patient->personnummer}}</p>
                <a class="text-blue-500 hover:underline" href="mailto:{{$patient->email}}">{{$patient->email}}</a>
                <a class="text-blue-500 hover:underline" href="tel:{{$patient->phone}}">{{$patient->phone}}</a>
                <div class="dropdown dropdown-bottom dropdown-end dropdown-hover">
                    <div tabindex="0" role="button" class="btn btn-wide btn-neutral">Manage Patient</div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <a href={{route('patients.edit', $patient)}}>Update</a>
                        </li>
                        <li>
                            <button class="text-red-700" onclick="delete_confirmation.showModal()">Delete</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col items-center text-base bg-white p-6 mt-4 rounded-lg shadow-sm hover:shadow-md transition ease-in-out delay-50">
                <h2 class="text-xl font-bold mb-4">Treatments</h2>
                <ul class="list-disc list-inside">
                    @forelse ($treatments as $treatment)
                    <li>{{ $treatment->name }}</li>
                    @empty
                    <li>No treatments assigned.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        -->
        <div class="w-full mt-8">
            <div class="flex justify-end items-center">
                <div class="flex gap-2">
                    <a href="{{route('entries.create', $patient)}}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Entry</a>
                    <dialog id="delete_confirmation" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Confirm password to delete</h3>
                            <form action="{{route('patients.destroy', $patient)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div class="flex flex-row-reverse justify-start items-center mt-4 w-full">
                                    <x-danger-button class="ms-3">
                                        {{ __('Delete') }}
                                    </x-danger-button>
                                    <x-secondary-button class="ms-3" onclick="delete_confirmation.close()">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>
            </div>
            <div class="my-6">
                @foreach($entries as $entry)
                <div class="w-full bg-white p-6 border-b border-blue-950 rounded-sm shadow-sm hover:shadow-md transition ease-in-out delay-50 text-blue-950">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-xl">{{$entry->title}}</h2>
                        <a href="{{ route('entries.edit', $entry) }}" class="rounded-sm hover:bg-gray-200 text-blue-950 hover:border-blue-950 bg-gray-50 border border-slate-200 transition ease-in-out delay-50 py-0.5 px-8 rounded-sm">Edit</a>
                    </div>
                    <p class="mb-4">{{$entry->description}}</p>
                    <div class="flex justify-between items-center">
                        <div class="flex gap-4">
                            @foreach($entry->files as $file)
                            @if(strpos($file->mime, 'video') !== false)
                            <video onclick="(()=>{var video = document.getElementById('modal-video');video.innerHTML='';var source = document.createElement('source');source.setAttribute('src', '{{'/'.$file->path}}');source.setAttribute('type', '{{$file->mime}}');video.appendChild(source);video_modal.showModal();})()" class="w-24 h-24 rounded object-cover cursor-pointer">
                                <source src="{{'/'.$file->path}}" type="{{$file->mime}}">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            <img onclick="(() => {document.getElementById('modal-image').src='{{'/'.$file->path}}'; image_modal.showModal();})()" src="{{'/'.$file->path}}" class="w-24 h-24 rounded object-cover cursor-pointer" alt="Image" />
                            @endif
                            @endforeach
                        </div>
                        <!--
                        <div class="space-x-2">
                            <form action="{{ route('entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline btn-error">Delete</button>
                            </form>
                        </div>
                        -->
                    </div>
                    <div class="text-gray-600 font-bold mt-4 flex justify-end gap-4 text-xs">
                        <p class="format-date" data-date="{{$entry->updated_at}}">Updated </p>
                        <p class="format-date" data-date="{{$entry->created_at}}">Created </p>
                    </div>
                </div>
                @endforeach
                {{$entries->links('vendor.pagination.tailwind')}}
            </div>
        </div>
    </div>

    <div class="md:w-2/3 w-full mt-12">
        <div class="flex justify-end items-center">
            <div class="flex gap-2">
                <a href="{{route('entries.create', $patient)}}" class="btn btn-secondary">New Entry</a>
                <dialog id="delete_confirmation" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">Confirm password to delete</h3>
                        <form action="{{route('patients.destroy', $patient)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <!-- Password -->
                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Password')" />

                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="flex flex-row-reverse justify-start items-center mt-4 w-full">
                                <x-danger-button class="ms-3">
                                    {{ __('Delete') }}
                                </x-danger-button>
                                <x-secondary-button class="ms-3" onclick="delete_confirmation.close()">
                                    {{ __('Cancel') }}
                                </x-secondary-button>
                            </div>
                        </form>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>
            </div>
        </div>

        <!-- Video Modal -->
        <dialog id="video_modal" class="modal">
            <div class="modal-box max-w-screen-xl w-full flex justify-center items-center">
                <video id="modal-video" class="w-full aspect-video" controls>
                    Your browser does not support the video tag.
                </video>
            </div>
        </dialog>

        <!-- Image Modal -->
        <dialog id="image_modal" class="modal">
            <div class="modal-box max-w-screen-xl w-full flex justify-center items-center">
                <img id="modal-image" />
            </div>
        </dialog>

        <!-- Delete Confirmation Dialog -->
        <dialog id="deleteDialog" class="modal">
            <form method="post" class="modal-box">
                @csrf
                @method('DELETE')
                <h3 class="font-bold text-lg">Are you sure you want to delete this entry?</h3>
                <div class="modal-action">
                    <button type="submit" class="btn btn-error">Delete</button>
                    <button type="button" onclick="closeDialog()" class="btn">Cancel</button>
                </div>
            </form>
        </dialog>
    </div>
</div>

<script>
    function closeDialog() {
        const dialog = document.getElementById('deleteDialog');
        dialog.close();
    }
</script>
</x-app-layout>
