<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Update Entry for {{ $patient->name }}</h1>
        </div>
        <div>
            <form action="{{ route('entries.update', $journalEntry->id) }}" method="POST" class="w-full" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Correct method for update -->
                <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Name</span>
                    </div>
                    <input type="text" name="name" value="{{ $patient->name }}" disabled class="input input-disabled input-bordered w-full" required />
                </label>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Personnummer</span>
                    </div>
                    <input type="text" name="personnummer" value="{{ $patient->personnummer }}" disabled class="input-disabled input input-bordered w-full" required />
                </label>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Title</span>
                    </div>
                    <input type="text" name="title" value="{{ old('title', $journalEntry->title) }}" placeholder="Entry Title" class="input input-bordered w-full" required />
                </label>
                <label class="form-control">
                    <div class="label">
                        <span class="label-text">Entry Description</span>
                    </div>
                    <textarea class="textarea textarea-bordered h-48" name="description" placeholder="Description" required>{{ old('description', $journalEntry->description) }}</textarea>
                </label>
                <label class="form-control mt-4">
                    <div class="label">
                        <span class="label-text">Existing Files</span>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($journalEntry->files as $file)
                            <div class="relative group">
                                <div>File ID: {{ $file->id }}</div>
                                @if (strpos($file->mime, 'video') !== false)
                                    <video class="w-24 h-24 rounded object-cover cursor-pointer" onclick="showVideoModal('{{ asset($file->path) }}', '{{ $file->mime }}')">
                                        <source src="{{ asset($file->path) }}" type="{{ $file->mime }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset($file->path) }}" class="w-24 h-24 rounded object-cover cursor-pointer" onclick="showImageModal('{{ asset($file->path) }}')" alt="Image" />
                                @endif
                                <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="absolute top-0 right-0 m-1" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE') <!-- Correct method for delete -->
                                    <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </label>
                <label class="form-control mt-4">
                    <div class="label">
                        <span class="label-text">New Files</span>
                    </div>
                    <input type="file" name="files[]" class="file-input file-input-bordered file-input-md w-full" multiple />
                </label>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button type="submit" class="btn btn-wide btn-primary">Update</button>
                    <a class="btn btn-ghost" href="{{ route('patients.show', $patient->id) }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

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
</x-app-layout>

<script>
    function showVideoModal(videoSrc, mimeType) {
        const videoModal = document.getElementById('video_modal');
        const video = document.getElementById('modal-video');
        video.innerHTML = '';
        const source = document.createElement('source');
        source.setAttribute('src', videoSrc);
        source.setAttribute('type', mimeType);
        video.appendChild(source);
        videoModal.showModal();
    }

    function showImageModal(imageSrc) {
        const imageModal = document.getElementById('image_modal');
        const image = document.getElementById('modal-image');
        image.setAttribute('src', imageSrc);
        imageModal.showModal();
    }
</script>
