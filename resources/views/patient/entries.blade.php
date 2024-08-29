<!-- resources/views/patient/show.blade.php -->
@vite(['resources/js/display-dates.js'])
<script>
    function showImage(filePath) {
        const image_modal = document.getElementById("image_modal");
        const modal_image = document.getElementById("modal-image");
        modal_image.src = "/uploads/" + filePath;
        image_modal.classList.remove("hidden");
        image_modal.classList.add("block");
        document.body.classList.add("overflow-hidden");
    }

    function closeImageModal() {
        const modal_image = document.getElementById("modal-image");
        image_modal.classList.add("hidden");
        image_modal.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
    }


    function showVideo(filePath, mime) {
        console.log(filePath, mime);
        const video = document.getElementById('modal-video');
        video.innerHTML = "";
        const video_modal = document.getElementById('video_modal');
        var source = document.createElement('source');
        source.setAttribute('src', "/uploads/" + filePath);
        source.setAttribute('type', mime);
        video.appendChild(source);
        video_modal.classList.remove("hidden");
        video_modal.classList.add("block");
        document.body.classList.add("overflow-hidden");
        video.play();
    }


    function closeVideoModal() {
        const video = document.getElementById('modal-video');
        const video_modal = document.getElementById('video_modal');
        video_modal.classList.add("hidden");
        video_modal.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
        video.pause();
        video.currentTime = 0;

    }
</script>

<x-app-layout>
    @section('content')
        <div>
            <h1>Patient: {{ $patient->name }}</h1>
            <p>Email: {{ $patient->email }}</p>
            <p>Phone: {{ $patient->phone }}</p>

            <!-- Display a button/link for admins to view deleted entries -->
            @if (auth()->user()->isAdmin())
                <a href="{{ route('entries.deleted', $patient->id) }}" class="btn btn-warning">View Deleted Entries</a>
            @endif

            <ul>
                @foreach ($entries as $entry)
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
                <h1 class="text-2xl font-medium">Entries</h1>
                <a href="{{ route('entries.create', $patient) }}"
                    class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">New Entry</a>
            </div>
            <div class="my-6">
                @forelse($entries as $entry)
                    <div
                        class="mb-2 drop-shadow-sm w-full bg-white p-6 border border-slate-200
                    rounded-sm shadow-sm hover:bg-slate-50 transition ease-in-out delay-50 text-blue-950">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-xl">{{ $entry->title }}</h2>
                            <a href="{{ route('entries.edit', $entry) }}"
                                class="rounded-sm
                                hover:bg-slate-200 hover:border-slate-400 text-slate-900
                                bg-slate-50 border
                                border-slate-200 transition ease-in-out delay-50
                                py-2 px-8 text-sm">Update</a>
                        </div>
                        <p class="mb-4">{{ $entry->description }}</p>
                        <div class="flex justify-between items-center">
                            <div class="flex gap-4">
                                @foreach ($entry->files as $file)
                                    @if (strpos($file->mime, 'video') !== false)
                                        <video
                                            onclick="showVideo('{{ $file->path }}',
                            '{{ $file->mime }}')"
                                            class="w-24 h-24 rounded object-cover cursor-pointer">
                                            <source src="{{ '/uploads/' . $file->path }}" type="{{ $file->mime }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <img onclick="showImage('{{ $file->path }}')"
                                            src="{{ '/uploads/' . $file->path }}"
                                            class="rounded
                            border-slate-200 border w-24 h-24 object-cover cursor-pointer"
                                            alt="Image" />
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="text-slate-600 font-bold mt-4 flex justify-end gap-4 text-xs">
                            <p class="format-date" data-date="{{ $entry->updated_at }}">Updated </p>
                            <p class="format-date" data-date="{{ $entry->created_at }}">Created </p>
                        </div>
                    </div>
                @empty
                    <div
                        class="mb-2 drop-shadow-sm w-full bg-white p-6 border border-slate-200
                    rounded-sm shadow-sm hover:bg-slate-50 transition ease-in-out delay-50 text-blue-950">
                        <p class="w-full text-center">You have not created any entries for this patient yet, start by
                            <a class="underline text-blue-700
                            hover:text-blue-900"
                                href="{{ route('entries.create', $patient) }}">creating</a>
                            one
                        </p>
                    </div>
                @endforelse
                {{ $entries->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div id="video_modal"
        class="cursor-pointer hidden fixed z-30 left-0 top-0 w-full h-full
    bg-black overflow-scroll bg-opacity-70 flex justify-center items-center"
        onclick="closeVideoModal()">
        <div onclick="(function(event){event.stopPropagation();})(event);"
            class="cursor-default modal-box max-w-screen-xl w-full flex justify-center items-center">
            <video id="modal-video" class="w-full aspect-video" controls>
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="image_modal"
        class="p-24 cursor-pointer hidden fixed z-30 left-0 top-0 w-full h-full
    bg-black bg-opacity-70 overflow-scroll flex justify-center items-center"
        onclick="closeImageModal()">
        <div onclick="(function(event){event.stopPropagation();})(event);"
            class="cursor-default flex items-center justify-center bg-white max-w-screen-xl m-auto w-full">
            <img id="modal-image" />
        </div>
    </div>
</x-app-layout>
