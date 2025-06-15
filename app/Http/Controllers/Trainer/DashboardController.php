<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Module;
use App\Models\Absence;
use Illuminate\Support\Collection;
use App\Models\Attendance;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    public function index()
{
    $trainer = Auth::user();

    $modules = Module::where('trainer_id', $trainer->id)->with('attendances.trainee')->get();

    $traineesCount = $modules->pluck('attendances')->flatten()->pluck('trainee_id')->unique()->count();
    $modulesCount = $modules->count();

    $absencesCount = $modules->pluck('attendances')
        ->flatten()
        ->where('status', 'absent')
        ->count();

    $absencesPerModule = $modules->mapWithKeys(function ($module) {
        $absences = $module->attendances->where('status', 'absent')->count();
        return [$module->name => $absences];
    });

    // ➕ Upcoming modules
    $upcomingModules = Module::where('trainer_id', $trainer->id)
        ->whereDate('start_date', '>=', Carbon::today())
        ->orderBy('start_date')
        ->take(5)
        ->get();

        $traineeAbsenceSummary = $modules->pluck('attendances')
    ->flatten()
    ->where('status', 'absent')
    ->groupBy('trainee_id')
    ->map(function ($group) {
        $trainee = $group->first()->trainee ?? null;
        return [
            'trainee' => $trainee,
            'absences' => $group->count(),
        ];
    })
    ->sortByDesc('absences');

    $recentAbsences = \App\Models\Attendance::with(['trainee', 'module'])
    ->where('status', 'absent')
    ->whereIn('module_id', $modules->pluck('id'))
    ->latest()
    ->take(5)
    ->get();

    return view('trainer.dashboard', compact(
    'modules',
    'traineesCount',
    'modulesCount',
    'absencesCount',
    'absencesPerModule',
    'upcomingModules',
    'traineeAbsenceSummary',
    'recentAbsences'
));
}
}
