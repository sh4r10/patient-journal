<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;
use App\Models\Note;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TreatmentController;

use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // PatientController.php

    public function index(Request $request)
    {
        $search = $request->input('search');
        $treatmentIds = $request->input('treatments', []);
        $query = Patient::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('personnummer', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($treatmentIds)) {
            $query->whereHas('treatments', function ($q) use ($treatmentIds) {
                $q->whereIn('treatment_id', $treatmentIds);
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(10);
        $allTreatments = Treatment::all();

        return view('patient.index', ['patients' => $patients, 'allTreatments' => $allTreatments]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $treatments = Treatment::all();
        return view('patient.create', compact('treatments'));
    }


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


    public function showTreatments(Patient $patient)
    {
        $treatments = $patient->treatments()->get();
        $allTreatments = Treatment::all();
        return view('patient.treatments', compact('patient', 'treatments', 'allTreatments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
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
        
         // Check if the user is an admin
    if (!Auth::user()->isAdmin()) {
        return redirect()->route('patients.index')->with('error', 'You do not have permission to delete patients.');
    }
        // Validate the user's password
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

       
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

    
    public function showNotes($id)
    {
        $patient = Patient::findOrFail($id);
        $notes = $patient->notes; 
    
        return view('patient.notes', compact('patient', 'notes'));
    }
    
    public function storeNote(Request $request, $id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    $patient = Patient::findOrFail($id);
    $patient->notes()->create([
        'content' => $request->input('content'),
        'created_at' => now(),
    ]);

    return redirect()->route('patients.notes', $id)->with('message', 'Note added successfully!');
}

    
    

public function destroyNote($id)
{
    $note = Note::findOrFail($id);
    $patientId = $note->patient_id;
    $note->delete();

    return redirect()->route('patients.notes', $patientId)->with('message', 'Note deleted successfully!');
}

    




public function assignTreatment(Request $request, Patient $patient)
{
    // Validate input
    $request->validate([
        'treatment_id' => 'required|exists:treatments,id',
    ]);

    $treatmentId = $request->input('treatment_id');

    // Check if the treatment is already assigned to the patient
    if ($patient->treatments()->where('treatments.id', $treatmentId)->exists()) {
        return redirect()->route('patients.treatments', $patient->id)
                         ->with('error', 'This treatment is already assigned to the patient.');
    }

    // Assign the treatment
    $patient->treatments()->attach($treatmentId);

    return redirect()->route('patients.treatments', $patient->id)
                     ->with('success', 'Treatment assigned successfully.');
}


    
    public function unassignTreatment(Patient $patient, Treatment $treatment)
    {
        $patient->treatments()->detach($treatment->id);

        return redirect()->route('patients.treatments', $patient)->with('message', 'Treatment unassigned successfully.');
    }

}
