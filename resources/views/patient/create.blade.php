<!-- resources/views/patient/create.blade.php -->
<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Create New Patient</h1>
        </div>
        <div>
            <form action="{{ route('patients.store') }}" method="POST" class="w-full">
                @csrf
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" placeholder="Patient Name" class="input input-bordered w-full" required />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Personnummer</span>
                        </div>
                        <input type="text" name="personnummer" placeholder="020112-XXXX" class="input input-bordered w-full" required />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Email</span>
                        </div>
                        <input type="email" name="email" placeholder="patient@dr.com" class="input input-bordered w-full" required />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Phone</span>
                        </div>
                        <input type="text" name="phone" placeholder="+4676XXXXXXX" class="input input-bordered w-full" required />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Treatments</span>
                        </div>
                        <select name="treatments[]" multiple class="select select-bordered w-full">
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Create</button>
                    <a class="border border-blue-950 bg-white hover:bg-gray-200 text-blue-950 rounded-sm py-2 px-8" href="{{ route('patients.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
