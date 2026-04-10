<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'type' => 'required|string',
            'title' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable',
        ]);

        Reminder::create($validated);

        return redirect()->back()->with([
            'message' => [
                'type' => 'success',
                'message' => 'Meeldetuletus lisatud!'
            ]
        ]);

        return Inertia::render('Pets/Show', [
            'pet' => $pet->loadMissing('reminders'),
        ]);
    }
    
}