<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id', 'user_id', 'type', 'name',
        'notes', 'reminder_date', 'reminder_time',
    ];

    protected $casts = [
        'reminder_date' => 'date',
    ];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}