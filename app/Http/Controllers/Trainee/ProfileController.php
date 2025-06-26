<?php
namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $modules = $user->modules()->with('trainer')->get();
        $absences = $user->absences()->with('module')->get();

        $totalAbsences = $absences->count();
        $justified = $absences->where('justified', true)->count();
        $unjustified = $absences->where('justified', false)->count();

        return view('trainee.profile', compact('user', 'modules', 'absences', 'totalAbsences', 'justified', 'unjustified'));
    }
}
