<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pet_id',
        'type',
        'name',
        'date',
        'time',
        'notification_sent',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'date',
        'time' => 'string', // keep simple (HH:mm)
        'notification_sent' => 'boolean',
    ];

    /**
     * The attributes that should be visible in arrays.
     */
    protected $visible = [
        'id',
        'pet_id',
        'type',
        'name',
        'date',
        'time',
        'notification_sent',
        'created_at',
        'updated_at',
    ];

    /**
     * Relationship: Reminder belongs to a pet
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get the type label in Estonian
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'vaktsiin' => 'Vaktsiin',
            'ravim' => 'Ravim',
            'arstivisiit' => 'Arstivisiit',
            default => $this->type,
        };
    }

    /**
     * Get the icon for the reminder type
     */
    public function getTypeIcon(): string
    {
        return match ($this->type) {
            'vaktsiin' => '💉',
            'ravim' => '💊',
            'arstivisiit' => '🏥',
            default => '🔔',
        };
    }

    /**
     * Check if reminder is overdue
     */
    public function isOverdue(): bool
    {
        $today = Carbon::today();

        return Carbon::parse($this->date)->lt($today) &&
               !$this->notification_sent;
    }

    /**
     * Check if reminder is today
     */
    public function isToday(): bool
    {
        return Carbon::parse($this->date)->isToday();
    }

    /**
     * Check if reminder is in the future
     */
    public function isFuture(): bool
    {
        return Carbon::parse($this->date)->gt(Carbon::today());
    }

    /**
     * Get formatted date and time
     */
    public function getFormattedDateTime(): string
    {
        return Carbon::parse($this->date)->format('d.m.Y') . ' ' . $this->time;
    }

    /**
     * Scope: reminders for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope: upcoming reminders (next 7 days)
     */
    public function scopeUpcoming($query)
    {
        return $query->whereBetween('date', [
                Carbon::today(),
                Carbon::today()->addDays(7),
            ])
            ->orderBy('date')
            ->orderBy('time');
    }

    /**
     * Scope: overdue reminders
     */
    public function scopeOverdue($query)
    {
        return $query->where('date', '<', Carbon::today())
            ->where('notification_sent', false)
            ->orderBy('date', 'desc');
    }

    /**
     * Scope: filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: filter by name
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Get unique names for a pet's reminders of a specific type
     */
    public static function getNamesByType($petId, $type)
    {
        return self::where('pet_id', $petId)
            ->where('type', $type)
            ->select('name')
            ->distinct()
            ->pluck('name')
            ->sort()
            ->values();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->time)) {
                $model->time = '09:00';
            }
        });
    }
}