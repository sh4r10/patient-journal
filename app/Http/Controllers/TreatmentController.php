<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query for treatments with search functionality
        $treatmentsQuery = Treatment::query();

        if ($search) {
            $treatmentsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $treatments = $treatmentsQuery->orderBy('name', 'asc')->paginate(10);

        // Determine the status for view rendering
        $noTreatments = Treatment::count() === 0; // Check if no treatments exist
        $noSearchResults = $search && $treatments->isEmpty(); // Check if search returned no results

        return view('treatments.index', compact('treatments', 'noTreatments', 'noSearchResults'));
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
        $treatment->deleted_by = Auth::user()->email;
        $treatment->save();
        $treatment->delete();

        return redirect()->route('treatments.index')->with('success', 'Treatment deleted successfully.');
    }
}
