<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;

class AbsenceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            $absences = Absence::with('trainee', 'module')->latest()->get();
            return view('admin.absences.index', compact('absences'));
        }

        if ($user->hasRole('Trainer')) {
            $absences = Absence::whereHas('module', function ($query) use ($user) {
                $query->where('trainer_id', $user->id);
            })->with('trainee', 'module')->latest()->get();

            return view('trainer.absences.index', compact('absences'));
        }

        abort(403);
    }

    public function update(Request $request, Absence $absence)
    {
        $request->validate([
            'is_excused' => 'required|boolean',
            'reason' => 'nullable|string|max:255'
        ]);

        $absence->update([
            'justified' => $request->is_excused,
            'reason' => $request->reason
        ]);

        return back()->with('success', 'Absence updated successfully.');
    }
}
