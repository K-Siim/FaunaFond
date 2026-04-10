<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created reminder in storage.
     */
    public function store(Request $request, Pet $pet)
    {
        // Authorize that user owns the pet
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate the incoming data
        $validated = $request->validate([
            'type' => 'required|in:vaktsiin,ravim,arstivisiit',
            'name' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'time' => 'nullable|date_format:H:i',
            'times' => 'array',
            'times.*' => 'date_format:H:i',
        ], [
            'type.required' => 'Meeldetuletuse tüüp on kohustuslik.',
            'type.in' => 'Valitud tüüp ei ole lubatud.',
            'name.required' => 'Nimi on kohustuslik.',
            'date.required' => 'Kuupäev on kohustuslik.',
            'date.date_format' => 'Kuupäev peab olema õige formaadis (YYYY-MM-DD).',
            'date.after_or_equal' => 'Kuupäev ei saa olla minevikus.',
        ]);

        // Handle multiple times for medications
        if ($validated['type'] === 'ravim' && !empty($validated['times'])) {
            foreach ($validated['times'] as $time) {
                Reminder::create([
                    'pet_id' => $pet->id,
                    'type' => $validated['type'],
                    'name' => $validated['name'],
                    'date' => $validated['date'],
                    'time' => $time,
                    'notification_sent' => false,
                ]);
            }
        } else {
            // Single time reminder
            Reminder::create([
                'pet_id' => $pet->id,
                'type' => $validated['type'],
                'name' => $validated['name'],
                'date' => $validated['date'],
                'time' => $validated['time'] ?? '09:00',
                'notification_sent' => false,
            ]);
        }

        return redirect()
            ->route('pets.show', $pet)
            ->with('message', [
                'message' => 'Meeldetuletus edukalt lisatud!',
                'type' => 'success',
            ]);
    }

    /**
     * Delete a reminder from storage.
     */
    public function destroy(Pet $pet, Reminder $reminder)
    {
        // Authorize that user owns the pet
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Make sure reminder belongs to this pet
        if ($reminder->pet_id !== $pet->id) {
            abort(404, 'Meeldetuletus ei ole leitud.');
        }

        $reminder->delete();

        return redirect()
            ->route('pets.show', $pet)
            ->with('message', [
                'message' => 'Meeldetuletus edukalt kustutatud!',
                'type' => 'success',
            ]);
    }

    /**
     * Mark a reminder as notified.
     */
    public function markNotified(Pet $pet, Reminder $reminder)
    {
        // Authorize that user owns the pet
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Make sure reminder belongs to this pet
        if ($reminder->pet_id !== $pet->id) {
            abort(404, 'Meeldetuletus ei ole leitud.');
        }

        $reminder->update(['notification_sent' => true]);

        return redirect()
            ->route('pets.show', $pet)
            ->with('message', [
                'message' => 'Meeldetuletus märgitud teavitatuks!',
                'type' => 'success',
            ]);
    }
}