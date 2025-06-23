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
));
}
}
