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
        $pets = Auth::user()->pets()
            ->orderBy('species')
            ->orderBy('name')
            ->get()
            ->append(['photo_url', 'formatted_dob', 'age']);

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

    public function show(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        $pet->load([
            'vetVisits',
            'vetVisits.files',
            'vaccines',
            'medications',
            'reminders.pet',
        ]);

        $pet->append(['photo_url', 'formatted_dob', 'age']);

        $vaccineExpiryReminders = $pet->vaccines
            ->filter(function ($v) {
                if (!$v->expiry_date) return false;
                $days = now()->startOfDay()->diffInDays(
                    \Carbon\Carbon::parse($v->expiry_date)->startOfDay(), false
                );
                return $days <= 14;
            })
            ->map(fn ($v) => [
                'id'         => 'auto_' . $v->id,
                'pet_name'   => $pet->name,
                'name'       => $v->name,
                'end_date'   => \Carbon\Carbon::parse($v->expiry_date)->format('d.m.Y'),
                'is_expired' => \Carbon\Carbon::parse($v->expiry_date)->isPast(),
            ])
            ->values();

        return Inertia::render('Pets/Show', [
            'pet' => $pet
                ->load(['vetVisits', 'vaccines', 'medications', 'vetVisits.files'])
                ->append(['photo_url', 'formatted_dob', 'age']),
            'vaccineExpiryReminders' => $vaccineExpiryReminders,
        ]);
    }

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

    public function destroy(Pet $pet)
    {
        $this->authorizePetOwner($pet);

        $pet->delete();

        return redirect()->route('pets.index');
    }

    public function edit(Pet $pet)
    {
        $this->authorizePetOwner($pet);
        

        return Inertia::render('Pets/Edit', [
            'pet' => $pet->append(['photo_url', 'formatted_dob', 'age']),
        ]);
    }

    private function authorizePetOwner(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }



}