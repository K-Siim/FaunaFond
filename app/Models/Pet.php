<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\VetVisit;
use App\Models\Vaccine;
use App\Models\Medication;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'chip',
        'species',
        'breed',
        'gender',
        'dob',
        'weight',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vetVisits()
    {
        return $this->hasMany(VetVisit::class)->orderBy('visit_date', 'desc');
    }

    public function vaccines()
    {
        return $this->hasMany(Vaccine::class)->orderBy('administered_date', 'desc');
    }

    public function medications()
    {
        return $this->hasMany(Medication::class)->orderBy('start_date', 'desc');
    }
}
