<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\JournalEntry;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = JournalEntry::with(['files' => function ($query) {
            $query->whereNull('deleted_at');
        }])->get();
        
        return view('journalEntries.index', ['entries' => $entries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $patientID)
    {
        $patient = Patient::where('id', $patientID)->first();
        return view('entry.create', ['patient' => $patient]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files[]' => 'nullable|mimes:png,jpeg',
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'patient_id' => ['required', 'string']
        ]);

        $patient = Patient::where('id', $request->patient_id)->first();

        $journal_entry = JournalEntry::create([
            'title' => $request->title,
            'description' => $request->description,
            'patient_id' => $request->patient_id,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $id = Uuid::uuid4();
                $filename = $id . '.' . $file->getClientOriginalExtension();
                $path = 'uploads' . '/' . $filename;
                Storage::disk('local')->put($path, file_get_contents($file), 'public');
                File::create([
                    'id' => $id,
                    'path' => $path,
                    'mime' => $file->getClientMimeType(),
                    'journal_entry_id' => $journal_entry->id
                ]);
            }
            return to_route('patients.show', $patient);
        }
        return to_route('patients.show', $patient);
    }

    /**
     * Display the specified resource.
     */
    public function show(JournalEntry $journalEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $entryID)
    {
        $journalEntry = JournalEntry::findOrFail($entryID);
        $patient = Patient::findOrFail($journalEntry->patient_id);
        return view('entry.edit', ['patient' => $patient, 'journalEntry' => $journalEntry]);
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $entryID)
    {
        $request->validate([
            'files[]' => 'nullable|mimes:png,jpeg',
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'patient_id' => ['required', 'string']
        ]);

        $journal_entry = JournalEntry::findOrFail($entryID);
        $patient = Patient::findOrFail($journal_entry->patient_id);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $id = Uuid::uuid4();
                $filename = $id . '.' . $file->getClientOriginalExtension();
                $path = 'uploads' . '/' . $filename;
                Storage::disk('local')->put($path, file_get_contents($file), 'public');
                File::create([
                    'id' => $id,
                    'path' => $path,
                    'mime' => $file->getClientMimeType(),
                    'journal_entry_id' => $journal_entry->id
                ]);
            }
            return to_route('patients.show', $patient);
        }

        $journal_entry->update(['title' => $request->title, 'description' => $request->description]);
        // Redirect to the patient's show page instead of the entries index
        return to_route('patients.show', $patient)->with('message', 'Entry updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalEntry $journalEntry)
    {
        try {
            // Attempt to delete the journal entry
            $journalEntry->delete();
            return redirect()->route('entries.index')->with('message', 'Entry deleted successfully');
        } catch (\Exception $e) {
            // If there's an exception, redirect back with an error message
            return back()->with('error', 'Error deleting entry: ' . $e->getMessage());
        }
    }

// Method to show deleted entries for a patient
public function showDeleted($patientID)
{
   /* if (!auth()->user()->isAdmin()) {
        abort(403); // Unauthorized access control
    }*/

    $patient = Patient::findOrFail($patientID);
    $deletedEntries = JournalEntry::onlyTrashed()->where('patient_id', $patientID)
        ->with(['files' => function ($query) {
            $query->withTrashed(); // Include trashed files if necessary
        }])->get();

    return view('entry.deleted', ['deletedEntries' => $deletedEntries, 'patient' => $patient]);
}


// Method to restore a deleted entry
public function restore($id)
{
    /*if (!auth()->user()->isAdmin()) {
        abort(403); 
    }*/

    $entry = JournalEntry::onlyTrashed()->findOrFail($id);
    $entry->restore();

    return redirect()->route('entries.show', $entry->id)->with('message', 'Entry restored successfully.');
}


}
