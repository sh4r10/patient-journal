@props(['patient', 'active'])

@php
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
@endphp

<div class="w-full flex flex-col items-center justify-center gap-4
text-yellow-800">
    <div
        class="w-fit flex bg-yellow-100 drop-shadow-sm px-8 rounded-sm
    justify-center items-center border border-yellow-200">
        <div>
            <div class="flex justify-start items-center mr-8 gap-2">
                <img class="rounded-full w-8"
                    src="https://ui-avatars.com/api/?name={{ $patient->name }}&size=128&background=d4c4aa&color=1b2748&rounded=true&format=svg"
                    alt="profile-picture">
                <h1 class="ml-2 text-blue-950 text-lg font-medium">{{ $patient->name }}</h1>
            </div>
        </div>

        <!-- Conditionally Render "Notes" -->
        @if ($isAdmin)
            @if ($active === 'notes')
                <a
                    class="border-b-2 border-blue-950 text-blue-950 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-slate-200">
                    {{ ucfirst('notes') }}</a>
            @else
                <a href="{{ route('patients.notes', $patient->id) }}"
                    class="border-b-2 border-yellow-100 hover:border-slate-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-slate-200">{{ ucfirst('notes') }}</a>
            @endif
        @endif

        @foreach (['entries', 'treatments', 'manage'] as $link)
            @if ($link === $active)
                <a
                    class="border-b-2 text-blue-950 border-blue-950 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-slate-200">
                    {{ ucfirst($link) }}</a>
            @else
                <a href="{{ route('patients.' . $link, $patient->id) }}"
                    class="border-b-2 border-yellow-100 hover:border-slate-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-slate-200">{{ ucfirst($link) }}</a>
            @endif
        @endforeach

    </div>
</div>
