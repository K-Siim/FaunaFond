<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'user_id',
        'name',
        'administered_date',
        'expiry_date',
        'batch_number',
    ];

    protected $casts = [
        'administered_date' => 'date',
        'expiry_date' => 'date',
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