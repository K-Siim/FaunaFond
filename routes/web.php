<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\VetVisitController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pet routes
    Route::resource('pets', PetController::class);

    // Vet visit routes
    Route::post('/pets/{pet}/vet-visits', [VetVisitController::class, 'store'])->name('vet-visits.store');
    Route::put('/pets/{pet}/vet-visits/{vetVisit}', [VetVisitController::class, 'update'])->name('vet-visits.update');
    Route::delete('/pets/{pet}/vet-visits/{vetVisit}', [VetVisitController::class, 'destroy'])->name('vet-visits.destroy');
});

require __DIR__.'/auth.php';