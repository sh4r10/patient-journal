<script>
    function showImage(e, filePath) {
        e.preventDefault();
        e.stopPropagation();
        const image_modal = document.getElementById("image_modal");
        const modal_image = document.getElementById("modal-image");
        modal_image.src = "/" + filePath;
        image_modal.classList.remove("hidden");
        image_modal.classList.add("block");
        document.body.classList.add("overflow-hidden");
    }

    function closeImageModal(e) {
        e.preventDefault();
        e.stopPropagation();
        const modal_image = document.getElementById("modal-image");
        image_modal.classList.add("hidden");
        image_modal.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
    }


    function showVideo(e, filePath, mime) {
        e.preventDefault();
        e.stopPropagation();
        const video = document.getElementById('modal-video');
        video.innerHTML = "";
        const video_modal = document.getElementById('video_modal');
        var source = document.createElement('source');
        source.setAttribute('src', "/" + filePath);
        source.setAttribute('type', mime);
        video.appendChild(source);
        video_modal.classList.remove("hidden");
        video_modal.classList.add("block");
        document.body.classList.add("overflow-hidden");
        video.play();
    }


    function closeVideoModal(e) {
        e.preventDefault();
        e.stopPropagation();
        const video = document.getElementById('modal-video');
        const video_modal = document.getElementById('video_modal');
        video_modal.classList.add("hidden");
        video_modal.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
        video.pause();
        video.currentTime = 0;

    }

    function deleteEntry(e) {
        e.preventDefault();
        e.stopPropagation();

        const input = prompt("Type `delete` to confirm the deletion.");
        if (input === "delete") {
            const form = document.getElementById("delete-entry-form");
            form.submit();
        } else {
            alert("Check failed!");
        }
    }
</script>

<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-8">
            <h1 class="text-2xl">Update Entry for {{ $patient->name }}</h1>
        </div>
        <h2 class="text-xl text-slate-700 mb-4">Manage Existing Files</h2>
        <div class="flex flex-wrap gap-4">
            @foreach ($journalEntry->files as $file)
                <div class="relative group">
                    @if (strpos($file->mime, 'video') !== false)
                        <video class="w-24 h-24 rounded object-cover
                        cursor-pointer"
                            onclick="showVideo(event, '{{ $file->path }}', '{{ $file->mime }}')">
                            <source src="{{ asset($file->path) }}" type="{{ $file->mime }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img src="{{ asset($file->path) }}"
                            class="w-24 h-24 rounded object-cover
                        cursor-pointer border-slate-200 border"
                            onclick="showImage(event, '{{ $file->path }}')" alt="Image" />
                    @endif
                    <form action="{{ route('files.destroy', $file->id) }}" method="POST"
                        class="absolute top-0 right-0 m-1"
                        onsubmit="return confirm('Are you sure you want to delete this file?');">
                        @csrf
                        @method('DELETE') <!-- Correct method for delete -->
                        <button type="submit"
                            class="bg-red-200
                        text-red-500 rounded-full hover:bg-red-600
                        hover:text-white p-1 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div>
            <h2 class="text-xl text-slate-700 mt-8 mb-4">Edit Entry</h2>
            <form action="{{ route('entries.update', $journalEntry->id) }}" method="POST" class="w-full"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                @csrf
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Title</span>
                    </div>
                    <input type="text" name="title" value="{{ $journalEntry->title }}" autofocus
                        class="rounded-sm mb-4 mt-2 input input-bordered w-full" required />
                </label>
                <label class="form-control">
                    <div class="label">
                        <span class="label-text">Entry Description</span>
                    </div>
                    <textarea class="w-full rounded-sm mb-4 mt-2 textarea textarea-bordered h-48" name="description" rows=5 required>{{ $journalEntry->description }}</textarea>
                </label>
                <label>Attach Additional Files</label>
                <input type="file" name="files[]" class="mt-4 file-input file-input-bordered file-input-md w-full"
                    multiple />
                <div class="w-full flex flex-wrap flex-row-reverse justify-between items-center gap-4 mt-8">
                    <div class="flex flex-row-reverse gap-2 flex-wrap">
                        <button type="submit"
                            class="drop-shadow bg-blue-950
                        hover:bg-blue-900 text-white rounded-sm py-2 px-8">Update</button>
                        <a class="border border-blue-950 bg-white hover:bg-slate-200
                        text-blue-950 rounded-sm py-2 px-8"
                            href="{{ route('patients.show', $patient) }}">Cancel</a>
                    </div>
                    <button
                        class="bg-red-50
                    hover:bg-red-100 border border-red-200 text-red-900 rounded-sm py-2
                    px-8"
                        onClick="deleteEntry(event)">Delete Entry</button>
                </div>
            </form>
        </div>
    </div>

    <form id="delete-entry-form" class="hidden" action="{{ route('entries.destroy', $journalEntry->id) }}"
        method="POST">
        @csrf
        @method('DELETE')
    </form>

    <!-- Video Modal -->
    <div id="video_modal"
        class="cursor-pointer hidden fixed z-30 left-0 top-0 w-full h-full
    bg-black bg-opacity-70 overflow-scroll flex justify-center items-center"
        onclick="closeVideoModal(event)">
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
    bg-black overflow-scroll bg-opacity-70 flex justify-center items-center"
        onclick="closeImageModal(event)">
        <div onclick="(function(event){event.stopPropagation();})(event);"
            class=" cursor-default flex items-center justify-center bg-white max-w-screen-xl m-auto w-full">
            <img id="modal-image" />
        </div>
    </div>

</x-app-layout>
