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
            'files.*' => 'required|file|mimes:png,jpeg,jpg,mp4,mov,avi',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('', $filename, 'minio');

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
        // Check if file exists
        $file = File::find($fileID);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Only delete the file from storage and the database if it exists
        try {

            $file->deleted_by = Auth::user()->email;
            $file->save();
            $file->delete();

            return redirect()->route('entries.edit', $file->journal_entry_id)
                ->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting file.');
        }
    }


    public function show(string $filename)
    {
        // Check if the file exists in the MinIO bucket
        if (!Storage::disk('minio')->exists($filename)) {
            abort(404);
        }

        // Retrieve the file's contents from the MinIO bucket
        $fileContent = Storage::disk('minio')->get($filename);

        // Get the MIME type of the file
        $mimeType = Storage::disk('minio')->mimeType($filename);

        // Return the file as a response with the correct headers
        return response()->stream(function () use ($fileContent) {
            echo $fileContent;
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
        ]);
    }





    public function restoreFile($id)
    {
        $file = File::onlyTrashed()->findOrFail($id);
        $file->restore();
        return back()->with('success', 'File restored successfully.');
    }
}
