<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\JournalEntry;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            'files.*' => 'nullable|mimes:png,jpeg,jpg,mp4,mov,avi',
            'title' => 'required|string',
            'description' => 'required|string',
            'patient_id' => 'required|string'
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $journal_entry = JournalEntry::create([
            'title' => $request->title,
            'description' => $request->description,
            'patient_id' => $request->patient_id,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $id = Uuid::uuid4();
                $filename = $id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('', $filename, 'minio');

                File::create([
                    'id' => $id,
                    'path' => $path,
                    'mime' => $file->getClientMimeType(),
                    'journal_entry_id' => $journal_entry->id
                ]);
            }
        }

        return redirect()->route('patients.show', $patient->id)->with('message', 'Journal entry created successfully.');
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
        $journalEntry = JournalEntry::with('files')->findOrFail($entryID);
        $patient = Patient::findOrFail($journalEntry->patient_id);
        return view('entry.edit', ['patient' => $patient, 'journalEntry' => $journalEntry]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $entryID)
    {


        // Validate the request
        $request->validate([
            'files.*' => 'nullable|file|mimes:png,jpeg,jpg,mp4,mov,avi',
            'title' => 'required|string',
            'description' => 'required|string',
            'patient_id' => 'required|string',
        ]);



        try {
            // Find the journal entry and patient
            $journal_entry = JournalEntry::findOrFail($entryID);
            $patient = Patient::findOrFail($journal_entry->patient_id);

            // Update the journal entry
            $journal_entry->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $id = Uuid::uuid4();
                    $filename = $id . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('', $filename, 'minio');

                    File::create([
                        'id' => $id,
                        'name' => $filename,
                        'path' => $path,
                        'mime' => $file->getClientMimeType(),
                        'journal_entry_id' => $journal_entry->id,
                    ]);
                }
            }



            // Redirect with success message
            return redirect()->route('patients.show', ['patient' => $patient->id])->with('message', 'Entry updated successfully.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to update entry.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) // Temporarily use $id instead of type-hinted model
    {
        $journalEntry = JournalEntry::find($id);
        $patient = Patient::find($journalEntry->patient_id);
        if (!$journalEntry) {

            return back()->with('error', 'Entry not found');
        }

        try {
            $journalEntry->deleted_by = Auth::user()->email;
            $journalEntry->save();
            $journalEntry->delete();
            return redirect()->route('patients.show', $patient)->with('message', 'Entry deleted successfully');
        } catch (\Exception $e) {

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
