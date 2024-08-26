<!-- resources/views/patient/create.blade.php -->
<x-app-layout>
    <div class="mt-12 max-w-screen-md m-auto">
        <div class="flex justify-start items-center mb-4">
            <h1 class="text-2xl">Create New Patient</h1>
        </div>
        <div>
            <form action="{{ route('patients.store') }}" method="POST" class="w-full">
                @csrf
                <div class="flex gap-6 mt-4 mb-8">
                    <label class="w-full">
                        <div class="text-sm text-slate-600 mb-2">
                            <span class="label-text">Name</span>
                        </div>
                        <input type="text" name="name" placeholder="Patient Name"
                        class="rounded mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                    </label>
                    <label class="w-full">
                        <div class="text-sm text-slate-600 mb-2>
                            <span class="label-text">Personnummer</span>
                        </div>
                        <input type="text" name="personnummer"
                        placeholder="020112-XXXX" class="rounded mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                    </label>
                </div>
                <div class="flex gap-6 mb-8">
                    <label class="w-full">
                        <div class="text-sm text-slate-600 mb-2>
                            <span class="label-text">Email</span>
                        </div>
                        <input type="email" name="email"
                        placeholder="patient@dr.com" class="rounded mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                    </label>
                    <label class="w-full">
                        <div class="text-sm text-slate-600 mb-2>
                            <span class="label-text">Phone</span>
                        </div>
                        <input type="text" name="phone"
                        placeholder="+4676XXXXXXX" class="rounded mt-2 focus:border-slate-500 focus:ring-0 w-full" required />
                    </label>
                </div>
                <div class="flex gap-6">
                    <label class="w-full">
                        <div class="text-sm text-slate-600 mb-2>
                            <span class="label-text">Treatments</span>
                        </div>
                        <p class="w-full bg-blue-100 p-4 text-blue-900
                        mt-2 mb-4 text-sm font-semibold rounded">Press and hold the CTRL key
                        to select multiple options</p>
                        <select name="treatments[]" multiple
                        class="appearance-none focus:border-slate-500 focus:ring-0 w-full select
                        select-bordered w-full rounded h-48">
                            @foreach($treatments as $treatment)
                                <option class="appearance-none" value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="w-full flex flex-row-reverse justify-start items-center gap-4 mt-4">
                    <button type="submit" class="drop-shadow bg-blue-950 hover:bg-blue-900 text-white rounded-sm py-2 px-8">Create</button>
                    <a class="border border-blue-950 bg-white hover:bg-slate-200 text-blue-950 rounded-sm py-2 px-8" href="{{ route('patients.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
