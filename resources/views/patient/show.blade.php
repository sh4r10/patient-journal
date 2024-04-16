<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl">{{$patient->name}}</h1>
            <div class="flex gap-2">
                <div class="dropdown dropdown-bottom dropdown-end dropdown-hover">
                    <div tabindex="0" role="button" class="btn btn-ghost">Manage Patient</div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <a href={{route('patients.edit', $patient)}}>Update</a>
                        </li>
                        <li>
                            <button class="text-red-700" onclick="delete_confirmation.showModal()">Delete</button>
                        </li>
                    </ul>
                </div>
                <a href="{{route('entries.create', $patient)}}" class="btn btn-primary">New Entry</a>
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
                <p>{{$entry->description}}</p>
                <div class="flex justify-between items-center">
                    <div class="flex gap-4">
                        @foreach($entry->files as $file)
                        <img onclick="(()=>{document.getElementById('modal-image').src='{{'/'.$file->path}}'; image_modal.showModal();})()" src="{{'/'.$file->path}}" class="w-24 h-24 rounded object-cover cursor-pointer" alt="Image" />
                        @endforeach
                    </div>
                    <div>
                        <a href="{{ route('entries.edit', $entry) }}" class="btn btn-sm btn-blue">Edit</a>
                        <button onclick="confirmDelete('{{ $entry->id }}')" class="btn btn-sm btn-red">Delete</button>
                    </div>
                </div>
                <div class="text-gray-600 font-bold mt-4 flex justify-end gap-4 text-xs">
                    <p>Created: {{$entry->created_at}}</p>
                    <p>Updated: {{$entry->updated_at}}</p>
                </div>
            </div>
            @endforeach
            {{$entries->links('vendor.pagination.tailwind')}}
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
