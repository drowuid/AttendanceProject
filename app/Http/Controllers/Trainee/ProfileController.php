<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Eager-load modules with trainer
        $modules = $user->modules()->with('trainer')->get();

        // Load absences with modules
        $absences = $user->absences()->with('module')->orderBy('date', 'desc')->get();

        // Calculate counts
        $totalAbsences = $absences->count();
        $justified = $absences->where('justified', true)->count();
        $unjustified = $absences->where('justified', false)->count();

        return view('trainee.profile', compact(
            'user',
            'modules',
            'absences',
            'totalAbsences',
            'justified',
            'unjustified',
            
        ));
    }

}
