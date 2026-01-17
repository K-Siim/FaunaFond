<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pets = Pet::where('user_id', auth()->id())->get();

        return Inertia::render('Dashboard', [
            'pets' => $pets,
        ]);
    }
}
