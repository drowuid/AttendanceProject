<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrainerReportController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Admin\AbsenceController as AdminAbsenceController;
use App\Http\Controllers\Trainer\AbsenceController as TrainerAbsenceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;

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
    // Route::get('/trainer/dashboard', [TrainerController::class, 'dashboard'])->name('trainer.dashboard');

    // Trainer module reports
    Route::get('/trainer/module-reports', [TrainerReportController::class, 'index'])->name('trainer.moduleReports');
    Route::get('/trainer/module-reports/{module}', [TrainerReportController::class, 'show'])->name('trainer.moduleReports.show');


    Route::get('/trainee/attendance', [AttendanceController::class, 'create'])->name('trainee.attendance.create');
    Route::post('/trainee/attendance', [AttendanceController::class, 'store'])->name('trainee.attendance.store');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    // Trainer-only routes
    Route::middleware(['role:trainer'])->group(function () {
        Route::get('/trainer/reports', [TrainerController::class, 'reports'])->name('trainer.reports');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Absence management routes
    Route::get('/absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::put('/absences/{absence}', [AbsenceController::class, 'update'])->name('absences.update');

    // Admin routes for managing absences
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/absences/{absence}/edit', [AdminAbsenceController::class, 'edit'])->name('absences.edit');
        Route::put('/absences/{absence}', [AdminAbsenceController::class, 'update'])->name('absences.update');
        Route::delete('/absences/{absence}', [AdminAbsenceController::class, 'destroy'])->name('absences.destroy');
        Route::get('/absences/trash', [AdminAbsenceController::class, 'trash'])->name('absences.trash');
        Route::put('/absences/{id}/restore', [AdminAbsenceController::class, 'restore'])->name('absences.restore');
        Route::delete('/absences/{id}/force', [AdminAbsenceController::class, 'forceDelete'])->name('absences.forceDelete');
        Route::get('/absences/export', [AdminAbsenceController::class, 'export'])->name('absences.export');
        Route::get('/absences/stats', [AdminAbsenceController::class, 'stats'])->name('absences.stats');
        Route::get('/absences/calendar', [AdminAbsenceController::class, 'calendar'])->name('absences.calendar');


    });

    // Trainer routes for managing absences
    Route::middleware(['role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/absences/{absence}/edit', [TrainerAbsenceController::class, 'edit'])->name('absences.edit');
        Route::put('/absences/{absence}', [TrainerAbsenceController::class, 'update'])->name('absences.update');
        Route::delete('/absences/{absence}', [TrainerAbsenceController::class, 'destroy'])->name('absences.destroy');
        Route::get('/absences/trash', [TrainerAbsenceController::class, 'trash'])->name('absences.trash');
        Route::put('/absences/{id}/restore', [TrainerAbsenceController::class, 'restore'])->name('absences.restore');
        Route::delete('/absences/{id}/force', [TrainerAbsenceController::class, 'forceDelete'])->name('absences.forceDelete');
        Route::get('/absences/export', [TrainerAbsenceController::class, 'export'])->name('absences.export');


    });
    // Admin dashboard route
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Trainer dashboard route
    Route::middleware(['role:trainer'])->group(function () {
        Route::get('/trainer/absences/calendar', [TrainerAbsenceController::class, 'calendar'])->name('trainer.absences.calendar');
        Route::get('/trainer/absences/stats', [TrainerAbsenceController::class, 'stats'])->name('trainer.absences.stats');
        Route::get('/trainer/dashboard', [TrainerDashboardController::class, 'index'])->name('trainer.dashboard');

    });
});

require __DIR__.'/auth.php';

