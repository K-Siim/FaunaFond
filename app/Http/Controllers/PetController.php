<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pets = Auth::user()->pets()->get();

        return Inertia::render('Pets/Index', [
            'pets' => $pets,
        ]);
    }

    public function create()
    {
        return Inertia::render('Pets/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'chip' => 'nullable|string|max:15',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'weight' => 'nullable|numeric',
            'image' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        Pet::create($data);

        return redirect()->route('pets.index');
    }

    public function show(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        return Inertia::render('Pets/Show', [
            'pet' => $pet
        ]);
    }

    public function update(Request $request, Pet $pet)
    {
        $this->authorizePetOwner($pet);

        $data = $request->validate([
            'name' => 'required|string',
            'chip' => 'nullable|string',
            'species' => 'required|string',
            'breed' => 'nullable|string',
            'gender' => 'nullable|string',
            'dob' => 'required|date',
            'weight' => 'nullable|numeric',
            'image' => 'nullable|string',
        ]);

        $pet->update($data);

        return redirect()->route('pets.index');
    }

    public function destroy(Pet $pet)
    {
        $this->authorizePetOwner($pet);
        $pet->delete();

        return redirect()->route('pets.index');
    }

    private function authorizePetOwner(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
