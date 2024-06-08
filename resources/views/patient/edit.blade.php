<!-- resources/views/patient/edit.blade.php -->
<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Edit Patient</h1>
        </div>
        <div>
            <form action="{{ route('patients.update', $patient) }}" method="POST" class="w-full">
                @csrf
                @method('PUT')
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" value="{{ $patient->name }}" class="input input-bordered w-full" required />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Personnummer</span>
                        </div>
                        <input type="text" name="personnummer" value="{{ $patient->personnummer }}" class="input input-bordered w-full" required />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Email</span>
                        </div>
                        <input type="email" name="email" value="{{ $patient->email }}" class="input input-bordered w-full" required />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Phone</span>
                        </div>
                        <input type="text" name="phone" value="{{ $patient->phone }}" class="input input-bordered w-full" required />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Treatments</span>
                        </div>
                        <select name="treatments[]" multiple class="select select-bordered w-full">
                            @foreach($treatments as $treatment)
                                <option value="{{ $treatment->id }}" @if($patient->treatments->contains($treatment->id)) selected @endif>
                                    {{ $treatment->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button class="btn btn-wide btn-primary">Update</button>
                    <a class="btn btn-ghost" href="{{ route('patients.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
