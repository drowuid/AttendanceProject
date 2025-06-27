<?php

namespace App\Http\Controllers\Trainee;

use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Module;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load the trainee's modules with trainer info
        $modules = $user->modules->load('trainer');

        // Get trainee absences with related module info
        $absences = Absence::with('module')
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        $totalAbsences = $absences->count();
        $justified = $absences->where('is_excused', true)->count();
        $unjustified = $absences->where('is_excused', false)->count();

        // Calculate expected attendance days based on all modules
        $expectedDays = $modules->reduce(function ($carry, $module) {
            $start = \Carbon\Carbon::parse($module->start_date);
            $end = \Carbon\Carbon::parse($module->end_date);
            return $carry + $start->diffInWeekdays($end) + 1;
        }, 0);

        $attendanceRate = $expectedDays > 0
            ? round((($expectedDays - $totalAbsences) / $expectedDays) * 100)
            : null;

        return view('trainee.dashboard', compact(
            'user', 'modules', 'absences', 'totalAbsences', 'justified', 'unjustified', 'attendanceRate'
        ));
    }

    public function modules()
{
    $user = Auth::user();

    $modules = $user->modules()
        ->with('trainer')
        ->orderBy('start_date', 'asc')
        ->get();

    $totalModules = $modules->count();
    $totalHours = $modules->sum('hours');

    return view('trainee.modules.index', compact('modules', 'totalModules', 'totalHours'));
}

public function absences()
{
    $user = Auth::user();

    $query = $user->absences()->with('module')->orderBy('date', 'desc');

    // Filtering
    if (request()->has('justified') && request()->justified !== '') {
        $query->where('justified', request()->justified);
    }

    if (request()->filled('from')) {
        $query->where('date', '>=', request()->from);
    }

    if (request()->filled('to')) {
        $query->where('date', '<=', request()->to);
    }

    $absences = $query->get();

    return view('trainee.absences.index', compact('absences'));
}

}
