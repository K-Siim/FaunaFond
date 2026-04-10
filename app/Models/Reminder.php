<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'type',
        'title',
        'date',
        'time',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}