<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Absence;
use App\Models\Module;
use App\Models\Course;

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

    // Absences per module
    $absencesPerModule = DB::table('absences')
        ->join('modules', 'absences.module_id', '=', 'modules.id')
        ->select('modules.name as module', DB::raw('count(*) as total'))
        ->groupBy('modules.name')
        ->pluck('total', 'module')
        ->toArray();

$absenceChartLabels = []; // e.g. ['Jan', 'Feb', 'Mar', ...]
$absenceChartData = [];   // e.g. [3, 5, 2, ...]

return view('admin.dashboard', compact(
    'userCount',
    'trainerCount',
    'absenceCount',
    'moduleCount',
    'courseCount',
    'activeSessions',
    'absenceChartLabels',
    'absenceChartData',
));
}
}
