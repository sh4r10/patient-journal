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

<div class="container mx-auto flex flex-col md:flex-row gap-8">
    <div class="md:w-1/3 w-full">
        <div class="flex flex-col items-center text-base bg-white p-12 rounded-lg shadow-sm hover:shadow-md transition ease-in-out delay-50">
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
        <div class="my-10">
            @foreach($entries as $entry)
            <div class="w-full bg-white p-6 mb-6 rounded shadow-sm hover:shadow-md transition ease-in-out delay-50">
                <h2 class="font-semibold text-xl mb-2">{{$entry->title}}</h2>
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
                    <div class="space-x-2">
                        <!-- Debug: Show Entry ID -->
                        <div>Entry ID: {{ $entry->id }}</div>

                        <a href="{{ route('entries.edit', $entry) }}" class="btn btn-sm btn-outline btn-warning">Update</a>
                        <form action="{{ route('entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline btn-error">Delete</button>
                        </form>
                    </div>
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

<!-- Video Modal -->
<dialog id="video_modal" class="modal">
    <div class="modal-box max-w-screen-xl w-full">
        <video id="modal-video" class="w-full aspect-video" controls>
            Your browser does not support the video tag.
        </video>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Image Modal -->
<dialog id="image_modal" class="modal">
    <div class="modal-box max-w-screen-xl w-full">
        <img id="modal-image" />
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function confirmDelete(entryId) {
        const form = document.querySelector('#deleteDialog form');
        form.action = `/entries/delete/${entryId}`;
        const dialog = document.getElementById('deleteDialog');
        dialog.showModal();
    }

    function closeDialog() {
        const dialog = document.getElementById('deleteDialog');
        dialog.close();
    }
</script>
</x-app-layout>
