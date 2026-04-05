<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pets = Pet::where('user_id', auth()->id())->get()->append('photo_url');

        return Inertia::render('Dashboard', [
            'pets' => $pets,
        ]);
    }
}
