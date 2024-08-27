<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            'files.*' => 'required|file|mimes:png,jpeg,jpg,mp4|max:2048',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');

                File::create([
                    'name' => $filename,
                    'path' => $path,
                    'mime' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->back()->with('message', 'Files uploaded successfully.');
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

    // Only delete the file from storage and the database if it exists
    try {
        if (Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }
        $file->deleted_by = Auth::user()->email;
        $file->delete();

        

        return redirect()->route('entries.edit', $file->journal_entry_id)
                         ->with('success', 'File deleted successfully.');
    } catch (\Exception $e) {
        Log::error('Error deleting file: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error deleting file.');
    }
}


public function show(string $filename)
{
    $path = 'uploads/' . $filename;
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return response()->file(storage_path('app/public/' . $path));
}




    public function restoreFile($id)
{
    $file = File::onlyTrashed()->findOrFail($id);
    $file->restore();
    return back()->with('success', 'File restored successfully.');
}


}
