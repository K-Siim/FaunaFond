<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VetVisitFile extends Model
{
    protected $fillable = ['vet_visit_id', 'original_name', 'path'];

    public function visit()
    {
        return $this->belongsTo(VetVisit::class);
    }
}