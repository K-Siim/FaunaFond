<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    public function store(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dose_amount' => 'required|numeric|min:0',
            'dose_unit' => 'required|string|max:50',
            'frequency_amount' => 'required|integer|min:1',
            'frequency_unit' => 'required|in:päevas,nädalas,kuus',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reminder_time' => 'nullable|date_format:H:i',
        ]);

        $pet->medications()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Ravim lisatud!');
    }

    public function update(Request $request, Pet $pet, Medication $medication)
    {
        if ($medication->pet_id !== $pet->id) {
            abort(403, 'This medication does not belong to this pet.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dose_amount' => 'required|numeric|min:0',
            'dose_unit' => 'required|string|max:50',
            'frequency_amount' => 'required|integer|min:1',
            'frequency_unit' => 'required|in:päevas,nädalas,kuus',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reminder_time' => 'nullable|date_format:H:i',
        ]);

        $medication->update($validated);

        return back()->with('success', 'Ravim uuendatud!');
    }

    public function destroy(Pet $pet, Medication $medication)
    {
        if ($medication->pet_id !== $pet->id) {
            abort(403, 'This medication does not belong to this pet.');
        }

        $medication->delete();

        return back()->with('success', 'Ravim kustutatud!');
    }
}