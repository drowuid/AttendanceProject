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
use App\Models\User;

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

        // Total attendance records
        $totalAttendance = Attendance::whereIn('module_id', $modules->pluck('id'))->count();

        // Absences count for trainer's modules
        $absencesCount = $modules->pluck('attendances')
            ->flatten()
            ->where('status', 'absent')
            ->count();

        // Absences per module - Fixed to return array format for charts
        $absencesPerModuleCollection = $modules->mapWithKeys(function ($module) {
            $absences = $module->attendances->where('status', 'absent')->count();
            return [$module->name => $absences];
        });

        // Convert to array for JavaScript
        $absencesPerModule = $absencesPerModuleCollection->toArray();

        // Absences over time (monthly) - Fixed to return proper format
        $absencesOverTimeCollection = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Convert to array and fill missing months with 0
        $absencesOverTime = $absencesOverTimeCollection->toArray();

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

        // Recent absences (Absence model) - Fixed query
        $recentAbsences = Absence::with(['trainee', 'module'])
            ->whereHas('module', function ($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->latest('date')
            ->take(10)
            ->get();

        // Absences by reason - Fixed to return array
        $absencesByReasonCollection = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereNotNull('reason')
            ->selectRaw('reason, COUNT(*) as total')
            ->groupBy('reason')
            ->pluck('total', 'reason');

        $absencesByReason = $absencesByReasonCollection->toArray();

        // Weekly absences setup
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Generate last 7 days for weekly chart
        $dates = collect(range(6, 0))->map(function ($i) {
            return Carbon::today()->subDays($i)->format('Y-m-d');
        });

        // Weekly absences data
        $weeklyAbsencesData = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereBetween('date', [Carbon::today()->subDays(6), Carbon::today()])
            ->selectRaw('DATE(date) as absence_date, COUNT(*) as total')
            ->groupByRaw('DATE(date)')
            ->pluck('total', 'absence_date');

        // Map dates to counts (fill missing dates with 0)
        $weeklyAbsenceCounts = $dates->mapWithKeys(function ($date) use ($weeklyAbsencesData) {
            return [$date => $weeklyAbsencesData[$date] ?? 0];
        })->toArray();

        // Weekly statistics
        $weeklyAbsencesCount = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNull('deleted_at')
            ->count();

        $justifiedCount = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('justified', true)
            ->whereNull('deleted_at')
            ->count();

        $unjustifiedCount = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('justified', false)
            ->whereNull('deleted_at')
            ->count();

        $justificationRate = $weeklyAbsencesCount > 0
            ? round(($justifiedCount / $weeklyAbsencesCount) * 100, 1)
            : 0;

        // Latest absences (Absence model)
        $latestAbsences = Absence::with(['trainee', 'module'])
            ->whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->latest('date')
            ->take(5)
            ->get();

        // Top trainees by absences - Fixed query
        $topTraineesCollection = Absence::whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->with('trainee')
            ->get()
            ->groupBy('trainee_id')
            ->map(function ($absences) {
                $trainee = $absences->first()->trainee;
                return [
                    'name' => $trainee ? $trainee->name : 'Unknown',
                    'total' => $absences->count()
                ];
            })
            ->sortByDesc('total')
            ->take(5);

        // Convert to array format for chart
        $topTrainees = $topTraineesCollection->mapWithKeys(function ($item) {
            return [$item['name'] => $item['total']];
        })->toArray();


         // New: Top 5 justified trainees
    $topJustifiedTrainees = DB::table('absences')
        ->select('user_id', DB::raw('COUNT(*) as justified_count'))
        ->where('justified', 1)
        ->groupBy('user_id')
        ->orderByDesc('justified_count')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            $user = User::find($item->user_id);
            return [
                'name' => $user?->name ?? 'Unknown',
                'id' => $user?->id,
                'count' => $item->justified_count,
            ];
        });

$recentJustifiedAbsences = \App\Models\Absence::with(['trainee', 'module'])
    ->where('justified', 1)
    ->latest()
    ->take(5)
    ->get();

    $recentUnjustifiedAbsences = \App\Models\Absence::with(['trainee', 'module'])
    ->where('justified', 0)
    ->latest()
    ->take(5)
    ->get();


        return view('trainer.dashboard', [
            // Basic counts
            'modules' => $modules,
            'traineesCount' => $traineesCount,
            'modulesCount' => $modulesCount,
            'absencesCount' => $absencesCount,
            'totalAttendance' => $totalAttendance,

            // Chart data (arrays for JavaScript)
            'absencesPerModule' => $absencesPerModule,
            'absencesOverTime' => $absencesOverTime,
            'absencesByReason' => $absencesByReason,
            'weeklyAbsenceCounts' => $weeklyAbsenceCounts,
            'topTrainees' => $topTrainees,

            // Justified/Unjustified counts
            'justifiedCount' => $justifiedCount,
            'unjustifiedCount' => $unjustifiedCount,

            // Other data
            'upcomingModules' => $upcomingModules,
            'traineeAbsenceSummary' => $traineeAbsenceSummary,
            'recentAbsences' => $recentAbsences,
            'latestAbsences' => $latestAbsences,
            'trainer' => $trainer,

            // Statistics
            'weeklyAbsencesCount' => $weeklyAbsencesCount,
            'justificationRate' => $justificationRate,

            // Alternative naming for consistency
            'totalModules' => $modulesCount,
            'totalAbsences' => $absencesCount,
            'totalTrainees' => $traineesCount,

            // Legacy arrays (if needed)
            'absenceModuleLabels' => array_keys($absencesPerModule),
            'absenceModuleData' => array_values($absencesPerModule),
            'weeklyAbsenceLabels' => $dates->toArray(),

            //Top justified trainees
            'topJustifiedTrainees' => $topJustifiedTrainees->toArray(),

            // Recent justified absences
            'recentJustifiedAbsences'=> $recentJustifiedAbsences->toArray(),
            'recentUnjustifiedAbsences' => $recentUnjustifiedAbsences->toArray(),
        ]);
    }
}
