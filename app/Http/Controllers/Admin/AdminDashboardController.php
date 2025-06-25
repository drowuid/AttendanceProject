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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;


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



        $topTrainees = Absence::with('trainee')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => optional($item->trainee)->name ?? 'Unknown',
                    'total' => $item->total,
                ];
            });

        // Attendance trends for the last 7 days
        $attendanceTrends = Attendance::select(
            DB::raw('DATE(date) as day'),
            DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present"),
            DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent"),
            DB::raw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late")
        )
            ->where('date', '>=', now()->subDays(6))
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get()
            ->keyBy('day');

        $trendDays = [];
        $presentData = [];
        $absentData = [];
        $lateData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendDays[] = Carbon::parse($date)->format('D');

            $presentData[] = $attendanceTrends[$date]->present ?? 0;
            $absentData[] = $attendanceTrends[$date]->absent ?? 0;
            $lateData[] = $attendanceTrends[$date]->late ?? 0;
        }

        $modulesOverview = Module::withCount([
            'absences as total_absences',
            'absences as justified_absences' => function ($query) {
                $query->where('justified', true);
            },
            'absences as unjustified_absences' => function ($query) {
                $query->where('justified', false);
            }
        ])
            ->withCount(['trainees'])
            ->get();

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
            'topTrainees',
            'trendDays',
            'presentData',
            'absentData',
            'lateData',
            'modulesOverview',
        ));
    }

    public function exportModulesOverview(): StreamedResponse
{
    $filename = 'modules_attendance_overview_' . now()->format('Ymd_His') . '.csv';

    $modules = Module::withCount([
        'absences as total_absences',
        'absences as justified_absences' => function ($query) {
            $query->where('justified', true);
        },
        'absences as unjustified_absences' => function ($query) {
            $query->where('justified', false);
        },
        'trainees'
    ])->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $columns = ['Module', 'Trainees', 'Total Absences', 'Justified', 'Unjustified'];

    $callback = function () use ($modules, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($modules as $module) {
            fputcsv($file, [
                $module->name,
                $module->trainees_count,
                $module->total_absences,
                $module->justified_absences,
                $module->unjustified_absences,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function exportTopTrainees(): StreamedResponse
{
    $topTrainees = Absence::with('trainee')
        ->select('user_id', DB::raw('count(*) as total'))
        ->groupBy('user_id')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return [
                'Trainee' => optional($item->trainee)->name ?? 'Unknown',
                'Absences' => $item->total,
            ];
        });

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="top_trainees.csv"',
    ];

    $callback = function () use ($topTrainees) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Trainee', 'Absences']);
        foreach ($topTrainees as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}


}
