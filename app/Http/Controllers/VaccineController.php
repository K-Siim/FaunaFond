<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccineController extends Controller
{
    public function store(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'administered_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'batch_number' => 'nullable|string|max:255',
        ]);

        $pet->vaccines()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Vaktsiin lisatud!');
    }

    public function update(Request $request, Pet $pet, Vaccine $vaccine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'administered_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'batch_number' => 'nullable|string|max:255',
        ]);

        $vaccine->update($validated);

        return back()->with('success', 'Vaktsiin uuendatud!');
    }

    public function destroy(Pet $pet, Vaccine $vaccine)
    {
        $vaccine->delete();

        return back()->with('success', 'Vaktsiin kustutatud!');
    }
}