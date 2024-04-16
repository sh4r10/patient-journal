<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::query()->orderBy('created_at', 'desc')->paginate(10);
        return view('patient.index', ['patients' => $patients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patient.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'personnummer' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        $patient = Patient::create($data);

        return to_route('patients.show', $patient)->with('message', 'Patient created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $entries = JournalEntry::with('files')->where('patient_id', $patient->id)->orderBy('created_at', 'desc')->paginate(5);

        return view('patient.show', ['patient' => $patient, 'entries' => $entries]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patient.edit', ['patient' => $patient]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'personnummer' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        $patient->update($data);

        return to_route('patients.show', $patient)->with('message', 'Patient updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Validate the user's password
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Find the patient by ID
        $patient = Patient::findOrFail($id);

        // Delete related records first (if necessary)
        // Example: $patient->appointments()->delete();

        // Delete the patient
        $patient->delete();

        // Redirect back to patients index page
        return redirect()->route('patients.index')->with('message', 'Patient deleted successfully');
    }
}
