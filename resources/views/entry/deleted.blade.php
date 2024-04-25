@extends('layouts.app')

@section('content')
<h1>Deleted Journal Entries for {{ $patient->name }}</h1>
@if (session('message'))
    <div>{{ session('message') }}</div>
@endif
<ul>
    @foreach ($deletedEntries as $entry)
        <li>{{ $entry->title }}
            <form action="{{ route('entries.restore', $entry->id) }}" method="POST">
                @csrf
                <button type="submit">Restore</button>
            </form>
        </li>
    @endforeach
</ul>
@endsection
