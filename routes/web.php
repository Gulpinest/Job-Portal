<?php

use App\Http\Controllers\PelamarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route yang hanya bisa diakses oleh admin
Route::middleware('admin')->group(function () {

});

// Route yang hanya bisa diakses oleh company
Route::middleware('company')->group(function () {

});

// Route yang hanya bisa diakses oleh pelamar
Route::middleware('pelamar')->group(function () {
    Route::get('/pelamar/data', [PelamarController::class, 'show'])->name('pelamar.profil');
    Route::get('/pelamar/data/edit', [PelamarController::class, 'edit'])->name('pelamar.edit');
    Route::put('/pelamar/data/update', [PelamarController::class, 'update'])->name('pelamar.update');
    Route::delete('/pelamar/data/delete', [PelamarController::class, 'destroy'])->name('pelamar.destroy');
});

require __DIR__.'/auth.php';
