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

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $trainerId = Auth::id();

    $query = Module::with(['attendances.trainee'])
        ->where('trainer_id', $trainerId);

    // Filtering logic
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->input('name') . '%');
    }

    if ($request->filled('start_date')) {
        $query->whereDate('start_date', '>=', $request->input('start_date'));
    }

    $modules = $query->get();

    $traineesCount = User::where('trainer_id', $trainerId)->count();
    $modulesCount = $modules->count();

    $absencesCount = Attendance::whereHas('module', function ($q) use ($trainerId) {
        $q->where('trainer_id', $trainerId);
    })->where('status', 'absent')->count();

    $absencesPerModule = $modules->mapWithKeys(function ($module) {
        $count = $module->attendances->where('status', 'absent')->count();
        return [$module->name => $count];
    });

    return view('trainer.dashboard', compact(
        'traineesCount', 'modulesCount', 'absencesCount',
        'absencesPerModule', 'modules'
    ));
}
}
