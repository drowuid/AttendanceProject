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
        // Get authenticated trainer
        $trainer = Auth::user();
        
        // Basic statistics
        $totalAbsences = Absence::count();
        $totalTrainees = \App\Models\User::where('role', 'trainee')->count();
        $totalModules = Module::count();
        $totalAttendance = Attendance::count();

        // Recent absences (last 10)
        $recentAbsences = Absence::with(['trainee', 'module'])
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Top absent trainees
        $topAbsentTrainees = \App\Models\User::where('role', 'trainee')
    ->withCount('absences')
    ->orderBy('absences_count', 'desc')
    ->limit(5)
    ->get()
    ->map(function ($trainee) {
        return [
            'id' => $trainee->id,
            'name' => $trainee->name,
            'absences_count' => $trainee->absences_count
        ];
    });

        // Top justified trainees
        $topJustifiedTrainees = \App\Models\User::where('role', 'trainee')
    ->withCount(['absences' => function ($query) {
        $query->where('justified', true);
    }])
    ->having('absences_count', '>', 0)
    ->orderBy('absences_count', 'desc')
    ->limit(5)
    ->get()
    ->map(function ($trainee) {
        return [
            'id' => $trainee->id,
            'name' => $trainee->name,
            'count' => $trainee->absences_count
        ];
    });

        // Recent justified absences
        $recentJustifiedAbsences = Absence::with(['trainee', 'module'])
            ->where('justified', true)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($absence) {
                return [
                    'date' => $absence->date,
                    'trainee' => [
                        'id' => $absence->trainee->id ?? null,
                        'name' => $absence->trainee->name ?? 'Unknown'
                    ],
                    'module' => [
                        'name' => $absence->module->name ?? 'Unknown Module'
                    ]
                ];
            });

        // Recent unjustified absences
        $recentUnjustifiedAbsences = Absence::with(['trainee', 'module'])
            ->where('justified', false)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($absence) {
                return [
                    'date' => $absence->date,
                    'trainee' => [
                        'id' => $absence->trainee->id ?? null,
                        'name' => $absence->trainee->name ?? 'Unknown'
                    ],
                    'module' => [
                        'name' => $absence->module->name ?? 'Unknown Module'
                    ]
                ];
            });

        // Chart data
        $chartData = $this->getChartData();

        return view('trainer.dashboard', array_merge(compact(
            'totalAbsences',
            'totalTrainees', 
            'totalModules',
            'totalAttendance',
            'recentAbsences',
            'topAbsentTrainees',
            'topJustifiedTrainees',
            'recentJustifiedAbsences',
            'recentUnjustifiedAbsences'
        ), $chartData));
    }

    private function getChartData()
    {
        // Absences per module
        $absencesPerModule = Module::withCount('absences')
            ->having('absences_count', '>', 0)
            ->get()
            ->pluck('absences_count', 'name')
            ->toArray();

        // Absences over time (last 12 months)
        $absencesOverTime = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('M Y');
            $count = Absence::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->count();
            $absencesOverTime[$monthKey] = $count;
        }

        // Absences by reason (if you have a reason field)
        $absencesByReason = Absence::select('reason', DB::raw('count(*) as count'))
            ->whereNotNull('reason')
            ->groupBy('reason')
            ->pluck('count', 'reason')
            ->toArray();

        // If no reason field, create dummy data
        if (empty($absencesByReason)) {
            $absencesByReason = [
                'Illness' => Absence::where('justified', true)->count(),
                'Personal' => Absence::where('justified', false)->count()
            ];
        }

        // Top trainees with most absences
        $topTrainees = User::where('role', 'student')
            ->withCount('absences')
            ->having('absences_count', '>', 0)
            ->orderBy('absences_count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('absences_count', 'name')
            ->toArray();

        // Weekly absence counts (last 8 weeks)
        $weeklyAbsenceCounts = [];
        for ($i = 7; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $weekKey = 'Week ' . $startOfWeek->format('M d');
            
            $count = Absence::whereBetween('date', [$startOfWeek, $endOfWeek])->count();
            $weeklyAbsenceCounts[$weekKey] = $count;
        }

        // Justified vs unjustified counts
        $justifiedCount = Absence::where('justified', true)->count();
        $unjustifiedCount = Absence::where('justified', false)->count();

        return [
            'absencesPerModule' => $absencesPerModule,
            'absencesOverTime' => $absencesOverTime,
            'absencesByReason' => $absencesByReason,
            'topTrainees' => $topTrainees,
            'weeklyAbsenceCounts' => $weeklyAbsenceCounts,
            'justifiedCount' => $justifiedCount,
            'unjustifiedCount' => $unjustifiedCount
        ];
    }
}