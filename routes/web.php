<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Login and logout routes should not be inside the 'auth' middleware group
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trainer dashboard for authenticated users
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');

    Route::get('/trainee/attendance', [AttendanceController::class, 'create'])->name('trainee.attendance.create');
    Route::post('/trainee/attendance', [AttendanceController::class, 'store'])->name('trainee.attendance.store');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/reports', [TrainerController::class, 'reports'])->name('trainer.reports');
});


Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});


});

require __DIR__.'/auth.php';

