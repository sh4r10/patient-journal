<div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-lg">
    <h1 class="text-xl sm:text-2xl mb-4">Upload a File</h1>

    <!-- Display Success Message -->
    @if (session('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- File Upload Form -->
    <form action="{{ route('file.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Journal Entry Selection -->
        <div class="form-group">
            <label for="journal_entry_id">Journal Entry</label>
            <select name="journal_entry_id" id="journal_entry_id" class="form-control w-full">
                @foreach ($journalEntries as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- File Input -->
        <div class="form-group">
            <label for="file">File</label>
            <input type="file" name="file" id="file" class="form-control w-full">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">Upload</button>
    </form>
</div>
