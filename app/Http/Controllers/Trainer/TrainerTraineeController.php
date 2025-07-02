<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\Absence;

class TrainerTraineeController extends Controller
{
    public function index()
    {
        $trainees = \App\Models\Trainee::with('course')->paginate(10);
        return view('trainer.trainees.index', compact('trainees'));
    }

    public function show($traineeId)
{
    $trainee = \App\Models\Trainee::findOrFail($traineeId);

    $absences = \App\Models\Absence::where('trainee_id', $traineeId)
        ->with('module')
        ->orderBy('date', 'desc')
        ->get();

    $modules = $trainee->modules()->pluck('name')->toArray();

    $totalAbsences = $absences->count();
    $justifiedAbsences = $absences->where('justified', true)->count();
    $unjustifiedAbsences = $absences->where('justified', false)->count();
    $moduleCount = count($modules);

    return view('trainer.trainee.profile', compact(
        'trainee',
        'absences',
        'modules',
        'totalAbsences',
        'justifiedAbsences',
        'unjustifiedAbsences',
        'moduleCount'
    ));
}
}