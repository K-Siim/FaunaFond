<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'pet' => Pet::first(),
        ]);
    }
}
