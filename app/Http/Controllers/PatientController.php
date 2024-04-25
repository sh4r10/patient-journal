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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Patient::query();

       //Include Soft-Deleted Patients for Admins
       /* if (auth()->user()->isAdmin()) {
            $query->withTrashed();
        }*/
    
        $patients = $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('personnummer', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(10);
    
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
            'email' => ['required', 'email'],
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


    // Assume we have a new view for this
    public function showDeleted()
    {
        $patients = Patient::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('patient.deleted', ['patients' => $patients]); 
    }
    

// Restores the soft-deleted patient
    public function restore($id)
{
    $patient = Patient::onlyTrashed()->findOrFail($id);
    $patient->restore(); 

    return redirect()->route('patients.index')->with('message', 'Patient restored successfully');
}

public function showDeletedEntries($patientID)
{
    /*if (!auth()->user()->isAdmin()) {
        abort(403);
    }*/

    $patient = Patient::onlyTrashed()->findOrFail($patientID);
    $deletedEntries = JournalEntry::onlyTrashed()->where('patient_id', $patientID)
        ->with(['files' => function ($query) {
            $query->withTrashed();
        }])->get();

    return view('patient.deleted', ['patient' => $patient, 'deletedEntries' => $deletedEntries]);
}



}
