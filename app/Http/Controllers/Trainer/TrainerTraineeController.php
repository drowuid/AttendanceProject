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
        $absences = $trainee->absences()->with('module')->latest()->take(10)->get();
        $modules = $trainee->modules()->pluck('name')->toArray();

        return view('trainer.trainees.show', compact('trainee', 'absences', 'modules'));
    }
}
