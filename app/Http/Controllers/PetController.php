<?php

namespace App\Http\Controllers;

use App\Models\Pet;

class PetController extends Controller
{
    public function show($id)
    {
        return Pet::findOrFail($id);
    }
}
