<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainee;

class TrainerTraineeController extends Controller
{

    public function index()
{
    $trainees = \App\Models\Trainee::with('course')->paginate(10);
    return view('trainer.trainees.index', compact('trainees'));
}


    public function show(Trainee $trainee)
{
    // Use user_id to get absences
    $absences = \App\Models\Absence::where('user_id', $trainee->user_id)
        ->with('module')
        ->latest()
        ->take(10)
        ->get();

    $modules = $trainee->modules()->pluck('name')->toArray();

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $absences->where('justified', false)->count();

    return view('trainer.trainee.profile', compact(
        'trainee',
        'absences',
        'modules',
        'total',
        'justified',
        'unjustified'
    ));
}

}
