<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Absence;
use App\Models\Module;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Carbon\Carbon;


class AdminDashboardController extends Controller
{
    public function index()
    {
        // Existing counts
        $userCount = User::count();
        $trainerCount = User::where('role', 'trainer')->count();
        $absenceCount = Absence::count();
        $moduleCount = Module::count();
        $courseCount = Course::count();
        $activeSessions = 0;

        // New counts for dashboard cards
        $traineesCount = User::where('role', 'trainee')->count();
        $absencesCount = Attendance::where('status', 'absent')->count();
        $modulesCount = Module::count();

        // Absences per module
        $absencesPerModuleRaw = DB::table('absences')
            ->join('modules', 'absences.module_id', '=', 'modules.id')
            ->select('modules.name as module', DB::raw('count(*) as total'))
            ->groupBy('modules.name')
            ->get();

        // If no absences exist, ensure the chart variables are set to empty arrays
        $recentAbsentees = Absence::with(['trainee', 'module'])
            ->latest('date')
            ->take(5)
            ->get();

        $absenceModuleLabels = $absencesPerModuleRaw->pluck('module')->toArray();
        $absenceModuleData = $absencesPerModuleRaw->pluck('total')->toArray();



        // Ensure required variables for older chart exist (even if empty)
        $absenceChartLabels = [];
        $absenceChartData = [];

        $monthlyAbsenceData = Absence::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        $months = $monthlyAbsenceData->pluck('month');
        $totals = $monthlyAbsenceData->pluck('total');


        $absencesPerModule = Absence::with('module')
            ->selectRaw('module_id, COUNT(*) as total')
            ->groupBy('module_id')
            ->get()
            ->map(function ($record) {
                return [
                    'module' => $record->module->name ?? 'Unknown',
                    'total' => $record->total,
                ];
            });

        $moduleNames = $absencesPerModule->pluck('module');
        $moduleTotals = $absencesPerModule->pluck('total');

        $justifiedCount = Absence::where('justified', true)->count();
        $unjustifiedCount = Absence::where('justified', false)->count();

        $weeklyAbsences = Absence::select(
            DB::raw('DATE(date) as day'),
            DB::raw('COUNT(*) as total')
        )
            ->where('date', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->pluck('total', 'day')
            ->toArray();

        // Fill missing days with 0
        $days = [];
        $totals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $days[] = Carbon::parse($date)->format('D');
            $totals[] = $weeklyAbsences[$date] ?? 0;

            $absencesByReason = Absence::select('reason', DB::raw('COUNT(*) as total'))
                ->groupBy('reason')
                ->orderBy('total', 'desc')
                ->pluck('total', 'reason')
                ->toArray();
        }

        $justifiedStats = Absence::select('justified', DB::raw('COUNT(*) as total'))
            ->groupBy('justified')
            ->pluck('total', 'justified')
            ->toArray();

        // Normalize keys
        $justifiedAbsences = [
            'Justified' => $justifiedStats[1] ?? 0,
            'Unjustified' => $justifiedStats[0] ?? 0,
        ];

        $weeklyAbsences = Absence::select(DB::raw('DATE(date) as day'), DB::raw('count(*) as total'))
            ->whereDate('date', '>=', now()->subDays(6))
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->pluck('total', 'day')
            ->toArray();

        // Fill in missing days with 0
        $allDays = collect(range(0, 6))->mapWithKeys(function ($i) {
            return [now()->subDays(6 - $i)->format('Y-m-d') => 0];
        });

        $weeklyAbsencesFilled = array_merge($allDays->toArray(), $weeklyAbsences);

        $reasonCounts = Absence::select('reason', DB::raw('count(*) as total'))
    ->groupBy('reason')
    ->pluck('total', 'reason')
    ->toArray();

        $recentUsers = User::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'userCount',
            'trainerCount',
            'traineesCount',
            'absenceCount',
            'absencesCount',
            'moduleCount',
            'modulesCount',
            'courseCount',
            'activeSessions',
            'absenceChartLabels',
            'absenceChartData',
            'absenceModuleLabels',
            'absenceModuleData',
            'recentUsers',
            'recentAbsentees',
            'monthlyAbsenceData',
            'months',
            'totals',
            'moduleNames',
            'moduleTotals',
            'justifiedCount',
            'unjustifiedCount',
            'days',
            'totals',
            'absencesByReason',
            'justifiedAbsences',
            'weeklyAbsences',
            'reasonCounts',
        ));
    }
}
