<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\InterviewScheduleController;
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

    Route::resource('resumes', ResumeController::class);
    Route::resource('lowongans', LowonganController::class);
    Route::resource('interview-schedules', InterviewScheduleController::class);
});

// Route yang hanya bisa diakses oleh admin
Route::middleware('admin')->group(function () {

});

// Route yang hanya bisa diakses oleh company
Route::middleware('company')->group(function () {

});

// Route yang hanya bisa diakses oleh pelamar
Route::middleware('pelamar')->group(function () {

});

require __DIR__.'/auth.php';
