<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Absence;
use App\Models\Module;
use App\Models\User;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::user();

        $traineesCount = User::where('trainer_id', $trainer->id)->count();
        $modulesCount = $trainer->modules()->count();

        $absencesCount = Absence::whereIn('module_id', $trainer->modules->pluck('id'))->count();

        $absencesPerModule = Absence::whereIn('module_id', $trainer->modules->pluck('id'))
            ->with('module')
            ->get()
            ->groupBy('module.name')
            ->map(fn($group) => $group->count());

        return view('trainer.dashboard', [
            'traineesCount' => $traineesCount,
            'modulesCount' => $modulesCount,
            'absencesCount' => $absencesCount,
            'absencesPerModule' => $absencesPerModule,
        ]);
    }
}
