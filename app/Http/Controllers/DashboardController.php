<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Reminder;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pets = Pet::where('user_id', auth()->id())->get()->append('photo_url');

        $reminders = Reminder::with('pet')
            ->where('user_id', auth()->id())
            ->orderBy('reminder_date')
            ->orderBy('reminder_time')
            ->get();

        $vaccineExpiryReminders = $pets->load('vaccines')
            ->flatMap(function ($pet) {
                return $pet->vaccines
                    ->filter(fn($v) => $v->expiry_date &&
                        now()->startOfDay()->diffInDays(
                            \Carbon\Carbon::parse($v->expiry_date)->startOfDay(), false
                        ) <= 14)
                    ->map(fn($v) => [
                        'id'         => 'auto_' . $v->id,
                        'pet_name'   => $pet->name,
                        'name'       => $v->name,
                        'end_date'   => \Carbon\Carbon::parse($v->expiry_date)->format('d.m.Y'),
                        'is_expired' => \Carbon\Carbon::parse($v->expiry_date)->isPast(),
                    ]);
            })->values();

        $medicationRepeatReminders = $pets->load('medications')
            ->flatMap(function ($pet) {
                return $pet->medications
                    ->filter(fn ($m) => $m->shouldShowTodayReminder())
                    ->map(fn ($m) => [
                        'id'            => 'med_' . $m->id,
                        'pet_name'      => $pet->name,
                        'name'          => $m->name,
                        'reminder_time' => $m->reminder_time,
                        'dose_amount'   => $m->dose_amount,
                        'dose_unit'     => $m->dose_unit,
                    ]);
            })->values();

        return Inertia::render('Dashboard', [
            'pets'                   => $pets,
            'reminders'              => $reminders,
            'vaccineExpiryReminders' => $vaccineExpiryReminders,
            'medicationRepeatReminders' => $medicationRepeatReminders,
        ]);
    }
}