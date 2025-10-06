<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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
});

// Route yang hanya bisa diakses oleh pelamar
Route::middleware('pelamar')->group(function () {

});

require __DIR__.'/auth.php';
