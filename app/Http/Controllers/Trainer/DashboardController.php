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
use Illuminate\Support\Facades\DB;

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

$absencesPerModule = Absence::whereHas('module', function ($query) use ($trainer) {
    $query->where('trainer_id', $trainer->id);
})->with('module')
  ->get()
  ->groupBy('module.name')
  ->map->count();

  $absencesOverTime = Absence::whereHas('module', function ($query) use ($trainer) {
    $query->where('trainer_id', $trainer->id);
})
->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as total")
->groupBy('month')
->orderBy('month')
->pluck('total', 'month');

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

    $recentAbsences = Attendance::with(['trainee', 'module'])
        ->where('status', 'absent')
        ->whereIn('module_id', $modules->pluck('id'))
        ->latest()
        ->take(5)
        ->get();

    // ✅ Absences per module chart data for Chart.js
    $absenceModuleLabels = $absencesPerModule->keys()->toArray();
    $absenceModuleData = $absencesPerModule->values()->toArray();

    $trainerId = Auth::id();

$topTrainees = Absence::whereHas('module', function ($query) use ($trainerId) {
    $query->where('trainer_id', $trainerId);
})
->join('modules', 'absences.module_id', '=', 'modules.id')
->join('attendances', function ($join) {
    $join->on('absences.module_id', '=', 'attendances.module_id');
})
->join('trainees', 'attendances.trainee_id', '=', 'trainees.id')
->select('trainees.name', DB::raw('count(distinct absences.id) as total'))
->groupBy('trainees.name')
->orderByDesc('total')
->limit(5)
->get()
->mapWithKeys(function ($item) {
    return [$item->name => $item->total];
});


    return view('trainer.dashboard', [
        'modules' => $modules,
        'traineesCount' => $traineesCount,
        'modulesCount' => $modulesCount,
        'absencesCount' => $absencesCount,
        'absencesPerModule' => $absencesPerModule,
        'upcomingModules' => $upcomingModules,
        'traineeAbsenceSummary' => $traineeAbsenceSummary,
        'recentAbsences' => $recentAbsences,
        'absenceModuleLabels' => $absenceModuleLabels,
        'absenceModuleData' => $absenceModuleData,
        'absencesOverTime' => $absencesOverTime
    ]);
}
}
