<?php

use App\Http\Controllers\PelamarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\PelamarInterviewController;
use App\Http\Controllers\AdminSkillController;
use App\Http\Controllers\AdminCompanyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyLamaranController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\PelamarLowonganController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
// use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriptionController;

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

    // Admin skill management
    Route::resource('admin/skills', AdminSkillController::class)->names('admin.skills');

    // Admin company verification
    Route::get('/admin/companies', [AdminCompanyController::class, 'index'])->name('admin.companies.index');
    Route::get('/admin/companies/{company}', [AdminCompanyController::class, 'show'])->name('admin.companies.show');
    Route::post('/admin/companies/{company}/verify', [AdminCompanyController::class, 'verify'])->name('admin.companies.verify');
    Route::post('/admin/companies/{company}/reject', [AdminCompanyController::class, 'reject'])->name('admin.companies.reject');
});

// Route yang hanya bisa diakses oleh company
Route::middleware('company')->group(function () {
    Route::get('/company/dashboard', [CompanyDashboardController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/company/lamarans', [CompanyLamaranController::class, 'index'])->name('company.lamarans.index');
    Route::get('/company/lamarans/{lamaran}', [CompanyLamaranController::class, 'show'])->name('company.lamarans.show');
    Route::post('/company/lamarans/{lamaran}/accept', [CompanyLamaranController::class, 'accept'])->name('company.lamarans.accept');
    Route::post('/company/lamarans/{lamaran}/reject', [CompanyLamaranController::class, 'reject'])->name('company.lamarans.reject');
    Route::resource('lowongans', LowonganController::class);

    // Interview schedules routes (simplified - per lowongan)
    Route::get('/interview-schedules', [InterviewScheduleController::class, 'index'])->name('interview-schedules.index');
    Route::get('/lowongans/{lowongan}/interview/create', [InterviewScheduleController::class, 'create'])->name('interview-schedules.create');
    Route::post('/lowongans/{lowongan}/interview', [InterviewScheduleController::class, 'store'])->name('interview-schedules.store');
    Route::get('/interview-schedules/{interviewSchedule}/show', [InterviewScheduleController::class, 'show'])->name('interview-schedules.show');
    Route::get('/interview-schedules/{interviewSchedule}/edit', [InterviewScheduleController::class, 'edit'])->name('interview-schedules.edit');
    Route::put('/interview-schedules/{interviewSchedule}', [InterviewScheduleController::class, 'update'])->name('interview-schedules.update');
    Route::delete('/interview-schedules/{interviewSchedule}', [InterviewScheduleController::class, 'destroy'])->name('interview-schedules.destroy');
    Route::post('/interview-schedules/{interviewSchedule}/completed', [InterviewScheduleController::class, 'markCompleted'])->name('interview-schedules.mark-completed');

    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
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
    Route::delete('/lamar/{lamaran}/withdraw', [LamaranController::class, 'withdraw'])->name('lamaran.withdraw');
    Route::get('/lamaran-saya', [PelamarLowonganController::class, 'lamaran_saya'])->name('lowongans.lamaran_saya');

    // Pelamar interview schedules
    Route::get('/jadwal-wawancara', [PelamarInterviewController::class, 'index'])->name('pelamar.interviews.index');
    Route::get('/jadwal-wawancara/{interviewSchedule}', [PelamarInterviewController::class, 'show'])->name('pelamar.interviews.show');
    Route::post('/jadwal-wawancara/{interviewSchedule}/hadir', [PelamarInterviewController::class, 'markAttended'])->name('pelamar.interviews.mark-attended');
    Route::post('/jadwal-wawancara/{interviewSchedule}/batalkan', [PelamarInterviewController::class, 'decline'])->name('pelamar.interviews.decline');

    Route::resource('skills', SkillController::class);

});

Route::post('/api/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
