<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Absence;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::user();
        $trainerId = $trainer->id;

        // Modules assigned to the trainer
        $modules = Module::where('trainer_id', $trainerId)
            ->with('attendances.trainee')
            ->get();

        $modulesCount = $modules->count();

        // Unique trainees in trainer's modules
        $traineesCount = $modules->pluck('attendances')
            ->flatten()
            ->pluck('trainee_id')
            ->unique()
            ->count();

        // Absences count for trainer's modules
        $absencesCount = $modules->pluck('attendances')
            ->flatten()
            ->where('status', 'absent')
            ->count();

        // Absences per module
        $absencesPerModule = $modules->mapWithKeys(function ($module) {
            $absences = $module->attendances->where('status', 'absent')->count();
            return [$module->name => $absences];
        });

        // Absences per module for chart
        $absenceModuleLabels = $absencesPerModule->keys()->toArray();
        $absenceModuleData = $absencesPerModule->values()->toArray();

        // Absences over time (monthly)
        $absencesOverTime = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Upcoming modules
        $upcomingModules = Module::where('trainer_id', $trainerId)
            ->whereDate('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Absence summary per trainee
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

        // Recent absences (Attendance model)
        $recentAbsences = Attendance::with(['trainee', 'module'])
            ->where('status', 'absent')
            ->whereIn('module_id', $modules->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        // Absences by reason
        $absencesByReason = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereNotNull('reason')
            ->selectRaw('reason, COUNT(*) as total')
            ->groupBy('reason')
            ->pluck('total', 'reason');

        // Weekly absences (last 7 days)
        $dates = collect(range(6, 0))->map(function ($i) {
            return Carbon::today()->subDays($i)->format('Y-m-d');
        });

        $weeklyAbsences = Absence::whereHas('module', function ($query) use ($trainerId) {
    $query->where('trainer_id', $trainerId);
})
->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])
->selectRaw('DATE(created_at) as absence_date, COUNT(*) as total')
->groupByRaw('DATE(created_at)')
->pluck('total', 'absence_date');

$startOfWeek = Carbon::now()->startOfWeek();
$endOfWeek = Carbon::now()->endOfWeek();

$weeklyAbsencesCount = Absence::whereHas('module', function ($query) use ($trainerId) {
        $query->where('trainer_id', $trainerId);
    })
    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
    ->whereNull('deleted_at')
    ->count();

        $weeklyAbsenceCounts = $dates->mapWithKeys(function ($date) use ($weeklyAbsences) {
            return [$date => $weeklyAbsences[$date] ?? 0];
        });

        $justifiedCount = Absence::whereHas('module', function ($query) use ($trainerId) {
        $query->where('trainer_id', $trainerId);
    })
    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
    ->where('justified', true)
    ->whereNull('deleted_at')
    ->count();

$justificationRate = $weeklyAbsencesCount > 0
    ? round(($justifiedCount / $weeklyAbsencesCount) * 100, 1)
    : 0;

// Calculate unjustified absences for the current week
$unjustifiedCount = Absence::whereHas('module', function ($query) use ($trainerId) {
        $query->where('trainer_id', $trainerId);
    })
    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
    ->where('justified', false)
    ->whereNull('deleted_at')
    ->count();

        // Latest absences (Absence model)
        $latestAbsences = Absence::with(['attendance.trainee', 'module'])
            ->whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Top trainees by absences
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

        // Totals for dashboard cards
        $totalModules = $modulesCount;
        $totalTrainerAbsences = $absencesCount;
        $uniqueTrainees = $traineesCount;

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
            'absencesOverTime' => $absencesOverTime,
            'topTrainees' => $topTrainees,
            'trainer' => $trainer,
            'totalModules' => $totalModules,
            'totalTrainerAbsences' => $totalTrainerAbsences,
            'uniqueTrainees' => $uniqueTrainees,
            'latestAbsences' => $latestAbsences,
            'absencesByReason' => $absencesByReason,
            'weeklyAbsenceCounts' => $weeklyAbsenceCounts,
            'weeklyAbsenceLabels' => $dates->toArray(),
            'weeklyAbsencesCount' => $weeklyAbsencesCount,
            'justificationRate' => $justificationRate,
            'justifiedCount' => $justifiedCount,
            'unjustifiedCount' => $unjustifiedCount,
        ]);
    }
}
