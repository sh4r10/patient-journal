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
        <x-patient-nav :patient="$patient" active="entries" />
        <div class="w-full mt-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl">Entries</h1>
                <a href="{{route('entries.create', $patient)}}" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Entry</a>
            </div>
            <div class="my-6">
                @foreach($entries as $entry)
                <div class="mb-2 drop-shadow-sm w-full bg-white p-6 border border-slate-200
                    rounded-sm shadow-sm hover:bg-gray-50 transition ease-in-out delay-50 text-blue-950">
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
        <dialog id="image_modal" class="open:block fixed z-20 left-0 top-0 w-screen h-full
        overflow-auto bg-white bg-opacity-70">
            <div class="max-w-screen-xl m-auto w-full flex justify-center items-center">
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
