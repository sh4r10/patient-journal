<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Journal Entry') }}
        </h2>
    </x-slot>

    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Update Entry for {{$patient->name}}</h1>
        </div>
        <div>
            <!-- Add a form ID for debugging -->
            <form id="update-entry-form" action="{{ route('entries.update', $journalEntry->id) }}" method="POST" class="w-full" enctype="multipart/form-data">
                <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                @csrf
                @method("PUT")
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

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $journalEntry->title) }}">
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $journalEntry->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Existing Files -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Existing Files:</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($journalEntry->files as $file)
                            <div class="relative group" id="file-{{ $file->id }}">
                                <div>File ID: {{ $file->id }}</div> <!-- Debug: Show File ID -->
                                @if (strpos($file->mime, 'video') !== false)
                                    <video class="w-24 h-24 rounded object-cover cursor-pointer" onclick="showVideoModal('{{ '/'.$file->path }}', '{{ $file->mime }}')">
                                        <source src="{{ '/'.$file->path }}" type="{{ $file->mime }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ '/'.$file->path }}" class="w-24 h-24 rounded object-cover cursor-pointer" onclick="showImageModal('{{ '/'.$file->path }}')" alt="Image" />
                                @endif
                                <form action="{{ route('files.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="absolute top-0 right-0 m-1 text-red-500 hover:text-red-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- New Files -->
                <div class="mb-4">
                    <label for="files" class="block text-gray-700 text-sm font-bold mb-2">New Files:</label>
                    <input type="file" name="files[]" id="files" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" multiple>
                    @error('files.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                        Update Entry
                    </button>
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

    document.getElementById('update-entry-form').addEventListener('submit', function(event) {
        console.log('Form submission triggered'); // Debug: log when form submission is triggered
    });
</script>
