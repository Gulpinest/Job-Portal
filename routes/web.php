<?php

use App\Http\Controllers\PelamarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\InterviewScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\PelamarLowonganController;
use App\Http\Controllers\AdminController;

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
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::get('/admin/logs/{log}', [AdminController::class, 'logDetail'])->name('admin.logs.detail');
    Route::post('/admin/logs/clear', [AdminController::class, 'clearLogs'])->name('admin.logs.clear');
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
    Route::get('/lamaran-saya', [PelamarLowonganController::class, 'lamaran_saya'])->name('lowongans.lamaran_saya');
});

require __DIR__.'/auth.php';
