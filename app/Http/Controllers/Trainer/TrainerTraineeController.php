<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\Absence;
use Illuminate\Http\Request;

class TrainerTraineeController extends Controller
{
    public function index()
    {
        $trainees = Trainee::with(['modules', 'absences'])
            ->orderBy('name')
            ->paginate(10);

        return view('trainer.trainee.index', compact('trainees'));
    }

    public function show($id)
{
    $user = \App\Models\User::where('role', 'trainee')->findOrFail($id);

    $absences = $user->absences()->with('module')->get();
    $modules = $user->modules()->get();

    return view('trainer.trainee.profile', [
        'trainee' => $user,
        'absences' => $absences,
        'modules' => $modules,
        'totalAbsences' => $absences->count(),
        'justifiedAbsences' => $absences->where('justified', true)->count(),
        'unjustifiedAbsences' => $absences->where('justified', false)->count(),
        'moduleCount' => $modules->count(),
    ]);
}



}
