<?php

namespace App\Http\Controllers;

use App\Models\VetVisit;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetVisitController extends Controller
{
    /**
     * Store a new vet visit log entry.
     */
    public function store(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'visit_date'  => 'required|date',
            'log'         => 'nullable|string',
        ]);

        $pet->vetVisits()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Arstivisiit lisatud!');
    }

    public function update(Request $request, Pet $pet, VetVisit $vetVisit)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'visit_date'  => 'required|date',
            'log'         => 'nullable|string',
        ]);

        $vetVisit->update($validated);

        return back()->with('success', 'Arstivisiit uuendatud!');
    }

    public function destroy(Pet $pet, VetVisit $vetVisit)
    {
        $vetVisit->delete();

        return back()->with('success', 'Arstivisiit kustutatud!');
    }
}