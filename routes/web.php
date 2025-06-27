<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AbsenceController as AdminAbsenceController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;
use App\Http\Controllers\Trainer\TrainerReportController;
use App\Http\Controllers\Trainer\TrainerStatisticsController;
use App\Http\Controllers\Trainer\AbsenceController as TrainerAbsenceController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Trainer\TrainerTraineeController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Trainee\DashboardController as TraineeDashboardController;



Route::get('/', [HomeController::class, 'index'])->name('home');

// Login and logout
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Common profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Calendar & attendance
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/trainee/attendance', [AttendanceController::class, 'create'])->name('trainee.attendance.create');
    Route::post('/trainee/attendance', [AttendanceController::class, 'store'])->name('trainee.attendance.store');

    // Absence index and update (shared)
    Route::get('/absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::put('/absences/{absence}', [AbsenceController::class, 'update'])->name('absences.update');

    // Admin-only routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/absences', [AdminAbsenceController::class, 'index'])->name('absences.index');
        Route::get('/absences/{absence}/edit', [AdminAbsenceController::class, 'edit'])->name('absences.edit');
        Route::put('/absences/{absence}', [AdminAbsenceController::class, 'update'])->name('absences.update');
        Route::delete('/absences/{absence}', [AdminAbsenceController::class, 'destroy'])->name('absences.destroy');
        Route::get('/absences/trash', [AdminAbsenceController::class, 'trash'])->name('absences.trash');
        Route::put('/absences/{id}/restore', [AdminAbsenceController::class, 'restore'])->name('absences.restore');
        Route::delete('/absences/{id}/force', [AdminAbsenceController::class, 'forceDelete'])->name('absences.forceDelete');
        Route::get('/absences/export', [AdminAbsenceController::class, 'export'])->name('absences.export');
        Route::get('/absences/stats', [AdminAbsenceController::class, 'stats'])->name('absences.stats');
        Route::get('/absences/calendar', [AdminAbsenceController::class, 'calendar'])->name('absences.calendar');
        Route::get('/modules/export', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'exportModulesOverview'])->name('modules.export');
        Route::get('/top-trainees/export', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'exportTopTrainees'])->name('admin.export.topTrainees');
        Route::get('/export/absences-by-reason', [AdminExportController::class, 'exportAbsencesByReason'])->name('admin.export.absencesByReason');
        Route::get('/export/justified-vs-unjustified', [AdminExportController::class, 'exportJustifiedVsUnjustified'])->name('admin.export.justifiedVsUnjustified');
        Route::get('/export/weekly-absences', [AdminExportController::class, 'exportWeeklyAbsences'])->name('admin.export.weeklyAbsences');
        Route::get('/trainees/{user}/profile', [\App\Http\Controllers\Admin\TraineeProfileController::class, 'show'])->name('trainees.profile');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/trainee-profile/{id}/export', [\App\Http\Controllers\Admin\TraineeProfileController::class, 'exportProfilePdf'])->name('trainees.exportProfile');
        Route::get('/admin/absences/{absence}/edit', [App\Http\Controllers\Admin\AbsenceController::class, 'edit'])->name('admin.absences.edit');
        Route::get('/admin/trainees/{id}/export-modules', [\App\Http\Controllers\Admin\TraineeProfileController::class, 'exportModulesCsv'])->name('admin.trainees.exportModules');
        Route::get('/admin/trainees', [\App\Http\Controllers\Admin\TraineeController::class, 'index'])->name('admin.trainees.index');
        Route::post('/admin/trainees/{id}/assign-modules', [\App\Http\Controllers\Admin\TraineeController::class, 'assignModules'])->name('admin.trainees.assignModules');



        Route::get('/trainers/{id}/profile', [\App\Http\Controllers\Admin\TrainerProfileController::class, 'show'])->name('trainers.profile');

        Route::get('/trainers/{id}/export-modules', [\App\Http\Controllers\Admin\TrainerProfileController::class, 'exportModulesCsv'])->name('trainers.exportModules');

        Route::get('/trainers/{id}/export-profile', [\App\Http\Controllers\Admin\TrainerProfileController::class, 'exportProfilePdf'])->name('trainers.exportProfile');



        // User role management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserRoleController::class, 'index'])->name('index');
            Route::get('/{user}/edit-role', [UserRoleController::class, 'edit'])->name('editRole');
            Route::put('/{user}/update-role', [UserRoleController::class, 'update'])->name('updateRole');
        });
    });


    // Trainer-only routes
    Route::middleware(['role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');

        // Absence management
        Route::get('/absences/{absence}/edit', [TrainerAbsenceController::class, 'edit'])->name('absences.edit');
        Route::put('/absences/{absence}', [TrainerAbsenceController::class, 'update'])->name('absences.update');
        Route::delete('/absences/{absence}', [TrainerAbsenceController::class, 'destroy'])->name('absences.destroy');
        Route::get('/absences/trash', [TrainerAbsenceController::class, 'trash'])->name('absences.trash');
        Route::put('/absences/{id}/restore', [TrainerAbsenceController::class, 'restore'])->name('absences.restore');
        Route::delete('/absences/{id}/force', [TrainerAbsenceController::class, 'forceDelete'])->name('absences.forceDelete');
        Route::get('/absences/export', [TrainerAbsenceController::class, 'export'])->name('absences.export');
        Route::get('/absences/calendar', [TrainerAbsenceController::class, 'calendar'])->name('absences.calendar');
        Route::get('/absences/stats', [TrainerAbsenceController::class, 'stats'])->name('absences.stats');

        // Reports & exports
        Route::get('/reports', [TrainerReportController::class, 'index'])->name('reports');
        Route::get('/reports/pdf', [TrainerReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/absence/email-summary', [\App\Http\Controllers\Trainer\TrainerReportController::class, 'exportAbsenceEmailSummary'])->name('absence.email.summary');
        Route::get('/reports/export/csv', [TrainerReportController::class, 'exportCsv'])->name('reports.export.csv');


        // Stats
        Route::get('/statistics', [TrainerStatisticsController::class, 'index'])->name('statistics');

        // Export absence stats
        Route::get('/export/absence-stats', [TrainerController::class, 'exportAbsenceStats'])->name('export.absences');
        Route::get('/export/absence-stats/pdf', [TrainerController::class, 'exportAbsenceStatsPdf'])->name('export.absences.pdf');

        // Trainee management
        Route::get('/trainees', [TrainerTraineeController::class, 'index'])->name('trainees.index');

    });

    // Trainee-only routes
    Route::middleware(['role:trainee'])
        ->prefix('trainee')
        ->name('trainee.')
        ->group(function () {
            Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [\App\Http\Controllers\Trainee\ProfileController::class, 'show'])->name('profile');
            Route::get('/profile/export/pdf', [App\Http\Controllers\Trainee\TraineeProfileExportController::class, 'exportProfilePdf'])->name('profile.export.pdf');
            Route::get('/profile/export/csv', [App\Http\Controllers\Trainee\TraineeProfileExportController::class, 'exportModulesCsv'])->name('profile.export.csv');
            Route::get('/modules', [App\Http\Controllers\Trainee\DashboardController::class, 'modules'])->name('modules.index');
            Route::get('/absences', [App\Http\Controllers\Trainee\DashboardController::class, 'absences'])->name('absences.index');
            Route::get('/absences/export-csv', [\App\Http\Controllers\Trainee\TraineeProfileExportController::class, 'exportAbsencesCsv'])->name('absences.export.csv');
            
        });

    // <-- This closes the Route::middleware(['auth'])->group(function () { block
});

require __DIR__ . '/auth.php';

