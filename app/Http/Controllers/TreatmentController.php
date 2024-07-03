<?php
namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Patient;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $treatmentIds = $request->input('treatments', []);

        // Fetch all treatments for the filter form
        $allTreatments = Treatment::all();

        // Fetch patients based on selected treatments
        $patientsQuery = Patient::query();

        if (!empty($treatmentIds)) {
            $patientsQuery->whereHas('treatments', function ($q) use ($treatmentIds) {
                $q->whereIn('treatment_id', $treatmentIds);
            });
        }

        if ($search) {
            $patientsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $patientsQuery->distinct()->paginate(10);

        return view('treatments.index', compact('allTreatments', 'patients'));
    }

    public function create()
    {
        return view('treatments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        Treatment::create($request->all());

        return redirect()->route('treatments.index')->with('success', 'Treatment created successfully.');
    }

    public function show(Treatment $treatment)
    {
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $treatment->update($request->all());

        return redirect()->route('treatments.index')->with('success', 'Treatment updated successfully.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return redirect()->route('treatments.index')->with('success', 'Treatment deleted successfully.');
    }
}
