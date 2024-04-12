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
        //
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
                $path = '/uploads' . '/' . $filename;
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
    public function edit(JournalEntry $journalEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JournalEntry $journalEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalEntry $journalEntry)
    {
        //
    }
}
