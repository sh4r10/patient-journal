<x-app-layout>
    @if ($errors->count() > 0)
    <script>
        window.onload = () => document.getElementById("delete_confirmation").showModal();
    </script>
    @endif
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl">{{$patient->name}}</h1>
            <div class="flex gap-2">
                <a class="btn btn-outline btn-warning" href={{route('patients.edit', $patient)}}>Update</a>
                <button class="btn btn-error btn-outline" onclick="delete_confirmation.showModal()">Delete</button>
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
        <div>
            Entries
        </div>
    </div>
</x-app-layout>
