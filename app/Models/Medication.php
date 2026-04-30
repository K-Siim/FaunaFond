<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
    'pet_id',
    'user_id',
    'name',
    'dose_amount',
    'dose_unit',
    'frequency_amount',
    'frequency_unit',
    'start_date',
    'end_date',
    'reminder_time',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'dose_amount' => 'decimal:2',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldShowTodayReminder(): bool
    {
        $today = now()->startOfDay();

        if ($this->start_date && $this->start_date->gt($today)) return false;
        if ($this->end_date   && $this->end_date->lt($today))   return false;

        if (! $this->reminder_time) return false;

        return match ($this->frequency_unit) {
            'päevas'  => true, 
            'nädalas' => $this->start_date->dayOfWeek === $today->dayOfWeek,
            'kuus'    => $this->start_date->day === $today->day,
            default   => false,
        };
    }
}