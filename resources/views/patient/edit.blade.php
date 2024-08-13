<!-- resources/views/patient/edit.blade.php -->
<x-app-layout>
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
            <div class="mt-12 max-w-screen-md m-auto w-full">
                <div class="flex justify-start items-center mb-4">
                    <h1 class="text-2xl">Edit Patient</h1>
                </div>
                <div>
                    <form action="{{ route('patients.update', $patient) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <div class="flex gap-6 mt-4 mb-8 w-full">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Name</span>
                                </div>
                                <input type="text" name="name"
                                                   value={{$patient->name}} class="mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                            </label>
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Personnummer</span>
                                </div>
                                <input type="text" name="personnummer"
                                                   value={{$patient->personnummer}} class="mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                            </label>
                        </div>
                        <div class="flex gap-6 mb-8">
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Email</span>
                                </div>
                                <input type="email" name="email"
                                                    value={{$patient->email}} class="mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                            </label>
                            <label class="w-full">
                                <div class="label">
                                    <span class="label-text">Phone</span>
                                </div>
                                <input type="text" name="phone"
                                                   value={{$patient->phone}} class="mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                            </label>
                        </div>
                        <div class="w-full flex flex-row-reverse justify-between items-center gap-4 mt-4">
                            <div class="flex flex-row-reverse">
                                <button class="btn btn-wide btn-primary">Update</button>
                                <a class="btn btn-ghost" href="{{ route('patients.index') }}">Cancel</a>
                            </div>
                            <a href="#" onClick="delete_confirmation.show()">Delete</a>
                        </div>
                    </form>
                </div>
            </div>

</x-app-layout>
