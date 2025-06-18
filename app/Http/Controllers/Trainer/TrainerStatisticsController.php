<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Absence;

class TrainerStatisticsController extends Controller
{
    public function index()
    {
        $trainerId = Auth::id();

        $modules = Module::where('trainer_id', $trainerId)->with(['absences' => function ($query) {
            $query->select('id', 'module_id', 'justified');
        }])->get();

        $statistics = $modules->map(function ($module) {
            $total = $module->absences->count();
            $justified = $module->absences->where('justified', true)->count();
            $unjustified = $module->absences->where('justified', false)->count();

            return [
                'module' => $module->name,
                'total' => $total,
                'justified' => $justified,
                'unjustified' => $unjustified,
            ];
        });

        return view('trainer.statistics.index', compact('statistics'));
    }
}
