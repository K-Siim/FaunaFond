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
    ];

    protected $casts = [
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
}