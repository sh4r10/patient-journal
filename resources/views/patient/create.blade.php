<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <!-- Simplicity is an acquired taste. - Katharine Gerould -->
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Create New Patient</h1>
        </div>
        <div>
            <form action={{route('patients.store')}} method="POST" class="w-full">
                @csrf
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" placeholder="Patient Name" class="input input-bordered w-full" />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Personnummer</span>
                        </div>
                        <input type="text" name="personnummer" placeholder="020112-XXXX" class="input input-bordered w-full" />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Email</span>
                        </div>
                        <input type="text" name="email" placeholder="patient@dr.com" class="input input-bordered w-full" />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Phone</span>
                        </div>
                        <input type="text" name="phone" placeholder="+4676XXXXXXX" class="input input-bordered w-full" />
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button class="btn btn-wide btn-primary">Create</button>
                    <a class="btn btn-ghost" href={{route('patients.index')}}>Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
