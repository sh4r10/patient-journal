<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $treatments = Treatment::all();
        return view('patient.create', compact('treatments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /*
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
    }*/
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'personnummer' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
        ]);

        $patient = Patient::create($request->only(['name', 'personnummer', 'email', 'phone']));
        if ($request->has('treatments')) {
            $patient->treatments()->attach($request->input('treatments'));
        }

        return to_route('patients.show', $patient)->with('message', 'Patient created successfully');
    }
    /**
     * Display the entries of the patient.
     */
   
public function show(Patient $patient)
{
    $entries = JournalEntry::with('files')->where('patient_id', $patient->id)->orderBy('created_at', 'desc')->paginate(5);

    return view('patient.entries', compact('patient', 'entries'));
}


public function showTreatments(Patient $patient){
    $treatments = $patient->treatments()->get();
    return view('patient.treatments', compact('patient', 'treatments'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $treatments = Treatment::all();
        return view('patient.edit', compact('patient', 'treatments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'personnummer' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
        ]);
    
        $patient->update($request->only(['name', 'personnummer', 'email', 'phone']));
    
        if ($request->has('treatments')) {
            $patient->treatments()->sync($request->input('treatments'));
        } else {
            $patient->treatments()->detach();
        }
    
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

        try {
            // Soft delete the patient, which should cascade to entries and files
            $patient->delete();  // This should trigger soft deletion of entries
            return redirect()->route('patients.index')->with('message', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
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
