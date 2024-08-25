<!-- resources/views/patient/edit.blade.php -->
<script>
    function showDeleteConfirmation(e){
        e.preventDefault();
        e.stopPropagation();
        const delete_confirmation = document.getElementById("delete_confirmation");
        delete_confirmation.classList.remove("hidden");
        delete_confirmation.classList.add("block");
        document.body.classList.add("overflow-hidden");
    }

    function closeDeleteConfirmation(e){
        e.preventDefault();
        e.stopPropagation();
        const delete_confirmation = document.getElementById("delete_confirmation");
        delete_confirmation.classList.add("hidden");
        delete_confirmation.classList.remove("block");
        document.body.classList.remove("overflow-hidden");
    }

    function submitForm(e){
        e.preventDefault();
        e.stopPropagation();
        const form = document.getElementById("delete-form");
        form.submit();
    }
</script>
<x-app-layout>
    <!-- Delete confirmation Modal -->
    <div onclick="closeDeleteConfirmation(event)" id="delete_confirmation" class="hidden fixed z-30 left-0 top-0 w-full h-full
        bg-black bg-opacity-70 flex justify-center items-center">
            <div class="bg-white max-w-screen-sm w-full p-8 rounded"
            onclick="showDeleteConfirmation(event)">
                <h3 class="font-medium text-gray-700 text-lg">Confirm password
                to delete patient</h3>
                <form action="{{route('patients.destroy', $patient)}}"
                method="post" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" class="text-md
                        font-semibold mb-2" :value="__('Password')" />

                            <x-text-input id="password" class="block py-3 mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="flex gap-2 flex-wrap flex-row-reverse justify-start items-center mt-4 w-full">
                            <button onclick="submitForm(event)" class="drop-shadow bg-red-700
                                hover:bg-red-800 text-white rounded-sm py-2
                                px-8">Delete</button>
                            <button class="bg-gray-100
                                hover:bg-gray-200 text-gray-900 rounded-sm py-2
                                px-8"
                                onClick="closeDeleteConfirmation(event)">Cancel</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
    </div>

    <div class="container mx-auto max-w-screen-lg mt-4">
        <x-patient-nav :patient="$patient" active="entries" />
        <div class="w-full mt-8">
                <div class="flex justify-start items-center mb-4">
                    <h1 class="text-2xl font-medium">Edit Patient</h1>
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
                        <div class="w-full flex flex-row-reverse justify-between
                        items-center flex-wrap">
                            <div class="flex gap-2">
                                <button type="submit" class="drop-shadow bg-blue-950
                                hover:bg-blue-900 text-white rounded-sm py-2
                                px-8">Update</button>
                                <a class="border border-blue-950 bg-white hover:bg-gray-200
                                text-blue-950 rounded-sm py-2 px-8" href="{{route('patients.show', $patient) }}">Cancel</a>
                            </div>
                            <button class="drop-shadow bg-red-100
                                hover:bg-red-200 text-red-900 rounded-sm py-2
                                px-8"
                                onClick="showDeleteConfirmation(event)">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>
