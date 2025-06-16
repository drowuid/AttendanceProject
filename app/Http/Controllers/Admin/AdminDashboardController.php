<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Absence;
use App\Models\Module;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

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

$absenceModuleLabels = $absencesPerModuleRaw->pluck('module')->toArray();
$absenceModuleData = $absencesPerModuleRaw->pluck('total')->toArray();

// Ensure required variables for older chart exist (even if empty)
$absenceChartLabels = [];
$absenceChartData = [];

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
));
}
}
