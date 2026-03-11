<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VetVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'user_id',
        'clinic_name',
        'visit_date',
        'log',
    ];

    protected $casts = [
        'visit_date' => 'date',
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