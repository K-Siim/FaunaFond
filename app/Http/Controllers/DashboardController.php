<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth; 
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 

        $pets = Pet::where('user_id', $user->id)->get();

        $reminders = Reminder::with('pet')
            ->where('user_id', $user->id)
            ->orderBy('reminder_date')
            ->orderBy('reminder_time')
            ->get();

        $vaccineExpiryReminders = $pets->load('vaccines')
            ->flatMap(function ($pet) {
                return $pet->vaccines
                    ->filter(fn($v) => $v->expiry_date &&
                        now()->startOfDay()->diffInDays(
                            Carbon::parse($v->expiry_date)->startOfDay(), false
                        ) <= 14)
                    ->map(fn($v) => [
                        'id'         => 'auto_' . $v->id,
                        'pet_name'   => $pet->name,
                        'name'       => $v->name,
                        'end_date'   => Carbon::parse($v->expiry_date)->format('d.m.Y'),
                        'is_expired' => Carbon::parse($v->expiry_date)->isPast(),
                    ]);
            })->values();

        return Inertia::render('Dashboard', [
            'pets'                   => $pets,
            'reminders'              => $reminders,
            'vaccineExpiryReminders' => $vaccineExpiryReminders,
        ]);
    }
}