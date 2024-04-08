<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <!-- Include Tailwind CSS or any other CSS framework if needed -->
</head>
<body>
    <div class="container">
        <h1>Upload a File</h1>

        <!-- Display Success Message -->
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- File Upload Form -->
        <form action="{{ route('file.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Journal Entry Selection -->
            <div class="form-group">
                <label for="journal_entry_id">Journal Entry</label>
                <select name="journal_entry_id" id="journal_entry_id" class="form-control">
                    @foreach ($journalEntries as $entry)
                        <option value="{{ $entry->id }}">{{ $entry->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- File Input -->
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</body>
</html>
