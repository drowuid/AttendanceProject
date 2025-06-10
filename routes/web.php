<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalendarController;

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

    // ✅ Add this route inside auth middleware if only logged-in users should see it
    Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');


    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


    Route::get('/trainee/attendance', [AttendanceController::class, 'create'])->name('trainee.attendance.create');
    Route::post('/trainee/attendance', [AttendanceController::class, 'store'])->name('trainee.attendance.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
});



});

require __DIR__.'/auth.php';

