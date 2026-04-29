<?php
namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'pet_id'        => ['required', 'integer', 'exists:pets,id'],
            'type'          => ['required', 'in:vaccine,medicine,vet_visit'],
            'name'          => ['required', 'string', 'max:200'],
            'notes'         => ['nullable', 'string', 'max:2000'],
            'reminder_date' => ['required', 'date'],
            'reminder_time' => ['nullable', 'date_format:H:i'],
        ]);

        abort_unless(
            Pet::where('id', $data['pet_id'])
               ->where('user_id', Auth::id())
               ->exists(),
            403
        );

        Reminder::create([...$data, 'user_id' => Auth::id()]);

        return back()->with('success', 'Meeldetuletus lisatud!');
    }

    public function destroy(Reminder $reminder)
    {
        abort_unless($reminder->user_id === Auth::id(), 403);
        $reminder->delete();

        return back()->with('success', 'Meeldetuletus kustutatud!');
    }
}