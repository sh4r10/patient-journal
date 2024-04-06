<x-layout>
    <x-layout>
        <div class="mt-12 max-w-screen-md m-auto">
            <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
            <div class="flex justify-start items-center mb-6">
                <h1 class="text-2xl">Update Patient</h1>
            </div>
            <div>
                <form actions={{route('patients.update')}} method="post" class="w-full">
                    <div class="flex gap-6">
                        <label class="w-2/4">
                            <span>Name</span>
                            <input type="text" value={{$patient->name}} class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                        </label>
                        <label class="w-2/4">
                            <span>Personnummer</span>
                            <input type="text" value={{$patient->personnummer}} class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                        </label>
                    </div>
                    <div class="flex gap-6">
                        <label class="w-2/4">
                            <span>Email</span>
                            <input type="email" value={{$patient->email}} class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                        </label>
                        <label class="w-2/4">
                            <span>Phone</span>
                            <input type="phone" value={{$patient->phone}} class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                        </label>
                    </div>
                    <div class="w-full flex flex-row-reverse justify-start items-center gap-4">
                        <button type="submit" class="btn btn-wide bg-emerald-700 py-2 px-6 mt-4 rounded text-white hover:bg-emerald-800 transition ease-in-out delay-50">Create New Patient</button>
                        <a href={{route('patients.index')}} cancel" class="btn btn-wide bg-gray-200 py-2 px-6 mt-4 rounded text-black hover:bg-gray-300 transition ease-in-out delay-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </x-layout>
