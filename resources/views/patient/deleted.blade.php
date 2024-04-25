{{-- resources/views/patient/deleted.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Deleted Journal Entries for {{ $patient->name }}</h1>
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <div class="card mt-4">
            @foreach ($deletedEntries as $entry)
                <div class="card-body">
                    <h5 class="card-title">{{ $entry->title }}</h5>
                    <p class="card-text">{{ $entry->description }}</p>
                    <ul>
                        @foreach ($entry->files as $file)
                            <li>{{ $file->name }} ({{ $file->deleted_at ? 'Deleted' : 'Active' }})</li>
                        @endforeach
                    </ul>
                    <form action="{{ route('entries.restore', $entry->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Restore Entry</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
