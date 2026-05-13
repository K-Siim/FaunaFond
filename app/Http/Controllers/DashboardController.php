<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Reminder;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pets = Pet::where('user_id', auth()->id())
            ->get()
            ->append('photo_url');

        $reminders = Reminder::with('pet')
            ->where('user_id', auth()->id())
            ->orderBy('reminder_date')
            ->orderBy('reminder_time')
            ->get();

        $vaccineExpiryReminders = $pets->load('vaccines')
            ->flatMap(function ($pet) {
                return $pet->vaccines
                    ->filter(fn ($v) =>
                        $v->expiry_date &&
                        now()->startOfDay()->diffInDays(
                            \Carbon\Carbon::parse($v->expiry_date)->startOfDay(),
                            false
                        ) <= 14
                    )
                    ->map(fn ($v) => [
                        'id'         => 'auto_' . $v->id,
                        'pet_name'   => $pet->name,
                        'name'       => $v->name,
                        'end_date'   => \Carbon\Carbon::parse($v->expiry_date)->format('d.m.Y'),
                        'is_expired' => \Carbon\Carbon::parse($v->expiry_date)->isPast(),
                    ]);
            })->values();

        $medicationRepeatReminders = collect();

        return Inertia::render('Dashboard', [
            'pets'                   => $pets,
            'reminders'              => $reminders,
            'vaccineExpiryReminders' => $vaccineExpiryReminders,
            'medicationRepeatReminders' => $medicationRepeatReminders,
        ]);
    }
}