@props([
'patient',
'active',
])

<div class="w-full flex flex-col items-center justify-center gap-4 text-blue-950">
    <div class="w-fit flex bg-white drop-shadow-sm px-8 rounded-sm justify-center items-center">
        <div>
            <div class="flex justify-start items-center mr-8 gap-2">
                <img class="rounded-full w-8" src="https://ui-avatars.com/api/?name={{$patient->name}}&size=128&background=d4c4aa&color=1b2748&rounded=true&format=svg" alt="profile-picture">
                <h1 class="text-lg font-medium">{{$patient->name}}</h1>
            </div>
        </div>
        @foreach(['entries', 'notes', 'treatments', 'manage'] as $link)
            @if($link === $active)
                <a class="border-b-2 border-blue-950 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">{{ucfirst($link)}}</a>
            @else
                <a href="{{ route('patients.'.$link, $patient->id) }}" class="border-b-2 border-white hover:border-gray-200 cursor-pointer px-4 py-6 transition ease-in-out delay-50 hover:bg-gray-200">{{ucfirst($link)}}</a>
            @endif
        @endforeach
    </div>
</div>