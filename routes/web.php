<?php

use App\Http\Controllers\PelamarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\InterviewScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\PelamarLowonganController;

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

    // Login Register Company
    Route::get('/company/login-register', [CompanyController::class, 'loginRegisterPage'])->name('company.login-register');

    // Show the login form
    Route::get('/company/login', [CompanyController::class, 'showLoginForm'])->name('company.login');

    // Handle the login request
    Route::post('/company/login', [CompanyController::class, 'login']);

    Route::get('/company/register', [CompanyController::class, 'showForm'])->name('company.register');
    Route::post('/company/register', [CompanyController::class, 'store'])->name('company.store');
});

// Route yang hanya bisa diakses oleh admin
Route::middleware('admin')->group(function () {

});

// Route yang hanya bisa diakses oleh company
Route::middleware('company')->group(function () {
    Route::resource('lowongans', LowonganController::class);
    Route::resource('interview-schedules', InterviewScheduleController::class);
});

// Route yang hanya bisa diakses oleh pelamar
Route::middleware('pelamar')->group(function () {
    Route::get('/pelamar/data', [PelamarController::class, 'show'])->name('pelamar.profil');
    Route::get('/pelamar/data/edit', [PelamarController::class, 'edit'])->name('pelamar.edit');
    Route::put('/pelamar/data/update', [PelamarController::class, 'update'])->name('pelamar.update');
    Route::delete('/pelamar/data/delete', [PelamarController::class, 'destroy'])->name('pelamar.destroy');

    Route::resource('resumes', ResumeController::class);

    Route::get('/lowongan-kerja', [PelamarLowonganController::class, 'index'])->name('lowongans.pelamar_index');
    Route::get('/lowongan-kerja/{lowongan}', [PelamarLowonganController::class, 'show'])->name('lowongans.detail');

    Route::post('/lamar', [LamaranController::class, 'store'])->name('lamaran.store');
});

require __DIR__.'/auth.php';
