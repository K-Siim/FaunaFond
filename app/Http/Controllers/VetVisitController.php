<?php

namespace App\Http\Controllers;

use App\Models\VetVisit;
use App\Models\VetVisitFile;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VetVisitController extends Controller
{
    public function store(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'visit_date'  => 'required|date',
            'log'         => 'nullable|string',
            'files.*'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $visit = $pet->vetVisits()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store("vet_visits/{$pet->id}", 'public');
                $visit->files()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Arstivisiit lisatud!');
    }

    public function update(Request $request, Pet $pet, VetVisit $vetVisit)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'visit_date'  => 'required|date',
            'log'         => 'nullable|string',
        ]);

        $vetVisit->update($validated);

        return back()->with('success', 'Arstivisiit uuendatud!');
    }

    public function destroy(Pet $pet, VetVisit $vetVisit)
    {
        $vetVisit->delete();

        return back()->with('success', 'Arstivisiit kustutatud!');
    }

    public function download(VetVisitFile $file)
    {
        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function uploadFiles(Request $request, Pet $pet, VetVisit $vetVisit)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:10240',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store("vet_visits/{$pet->id}", 'public');
            $vetVisit->files()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        return back()->with('success', 'Failid lisatud!');
    }
}