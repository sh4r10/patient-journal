<?php

namespace App\Http\Controllers;

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
            'files[]' => 'mimes:png,jpeg',
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'patient_id' => ['required', 'string']
        ]);

        $patient = Patient::where('id', $request->patient_id)->first();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put('uploads' . '/' . $filename, file_get_contents($file), 'public');
            }
            return to_route('patients.show', $patient);
        }
        return view('patient.index');
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
