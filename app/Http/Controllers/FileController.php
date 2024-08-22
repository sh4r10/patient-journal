<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Show the form for uploading a new file.
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm()
    {
        $journalEntries = JournalEntry::all(); // Retrieve all journal entries to select from in the view
        return view('upload', compact('journalEntries'));
    }

    /**
     * Store a newly uploaded file in storage and link it to a journal entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validate the file
            'journal_entry_id' => 'required|exists:journal_entries,id', // Ensure the journal entry exists
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique name for the file
        $filePath = Storage::putFileAs('uploads', $file, $fileName); // Save the file in the "uploads" directory

        File::create([
            'name' => $fileName,
            'path' => $filePath,
            'journal_entry_id' => $request->journal_entry_id, // Link the file to the journal entry
        ]);

        return back()->with('message', 'File uploaded successfully.'); // Redirect back with a success message
    }


    
    public function destroy(Request $request, string $fileID)
{
    Log::info('Attempting to delete file', ['file_id' => $fileID]);

    // Check if file exists
    $file = File::find($fileID);
    if (!$file) {
        Log::error('File not found', ['file_id' => $fileID]);
        return redirect()->back()->with('error', 'File not found.');
    }

    Log::info('File found', ['file_id' => $fileID, 'path' => $file->path]);

    // Delete the file from storage and the database
    Storage::disk('local')->delete($file->path);
    $file->delete();

    Log::info('File deleted successfully.', ['file_id' => $fileID]);

    // Redirect back to the journal entry edit page
    return redirect()->route('entries.edit', $file->journal_entry_id)
                     ->with('success', 'File deleted successfully.');
}


    



    public function show(string $filename)
    {
        $path = 'uploads/' . $filename;
        $file = File::where('path', $path)->firstOrFail();
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $fileContent = Storage::disk('local')->get($path);

        return response($fileContent, 200)->header('Content-Type', $file->mime);
    }

    public function restoreFile($id)
{
    $file = File::onlyTrashed()->findOrFail($id);
    $file->restore();
    return back()->with('success', 'File restored successfully.');
}


}
