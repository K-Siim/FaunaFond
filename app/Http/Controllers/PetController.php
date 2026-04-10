<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's pets.
     */
    public function index()
    {
        $pets = Auth::user()->pets()
            ->orderBy('species')
            ->orderBy('name')
            ->get()
            ->append(['photo_url', 'formatted_dob', 'age']);

        return Inertia::render('Pets/Index', [
            'pets' => $pets,
        ]);
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        return Inertia::render('Pets/Create');
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'chip'        => 'nullable|digits_between:1,15',
            'species'     => 'required|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'breed'       => 'nullable|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'gender'      => 'nullable|string|in:isane,emane',
            'weight'      => 'nullable|numeric|decimal:0,2|min:0',
            'dob'         => 'required|date',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data['user_id'] = Auth::id();

        $imageFile = $request->file('image');
        unset($data['image']);

        $pet = Pet::create($data);

        if ($imageFile) {
            $pet->addMedia($imageFile)
                ->toMediaCollection('pet-photos');
        }

        return redirect()->route('pets.index');
    }

    /**
     * Display the specified pet with all its data including reminders.
     */
    public function show(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        // Get unique vaccine, medication, and clinic names for the RemindersModal dropdown
        $vaccineNames = Reminder::where('pet_id', $pet->id)
            ->where('type', 'vaktsiin')
            ->distinct()
            ->pluck('name')
            ->sort()
            ->values();

        $medicationNames = Reminder::where('pet_id', $pet->id)
            ->where('type', 'ravim')
            ->distinct()
            ->pluck('name')
            ->sort()
            ->values();

        $clinicNames = Reminder::where('pet_id', $pet->id)
            ->where('type', 'arstivisiit')
            ->distinct()
            ->pluck('name')
            ->sort()
            ->values();

        return Inertia::render('Pets/Show', [
            'pet' => $pet
                ->load(['vetVisits', 'vaccines', 'medications', 'vetVisits.files', 'reminders'])
                ->append(['photo_url', 'formatted_dob', 'age']),
            'vaccineNames' => $vaccineNames,
            'medicationNames' => $medicationNames,
            'clinicNames' => $clinicNames,
        ]);
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        return Inertia::render('Pets/Edit', [
            'pet' => $pet->append(['photo_url', 'formatted_dob', 'age']),
        ]);
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $this->authorizePetOwner($pet);

        $data = $request->validate([
            'name'        => 'required|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'chip'        => 'nullable|digits_between:1,15',
            'species'     => 'required|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'breed'       => 'nullable|string|max:255|regex:/^[A-Z][a-zA-Z\s]*$/',
            'gender'      => 'nullable|string|in:isane,emane',
            'weight'      => 'nullable|numeric|decimal:0,2|min:0',
            'dob'         => 'required|date',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imageFile = $request->file('image');
        unset($data['image']);

        $pet->update($data);

        if ($imageFile) {
            $pet->clearMediaCollection('pet-photos');
            $pet->addMedia($imageFile)
                ->toMediaCollection('pet-photos');
        }

        return redirect()->route('pets.index');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        $pet->delete();

        return redirect()->route('pets.index');
    }

    /**
     * Authorize that the current user owns the pet.
     */
    private function authorizePetOwner(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}