<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Trainee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TrainerAbsenceStatsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Absence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $modules = Module::with('attendances.trainee')->get();

        return view('trainer.dashboard', compact('modules'));
    }

    public function reports()
{
    $modules = Module::with(['attendances.trainee'])->get();

    $traineeStats = [];

    foreach ($modules as $module) {
        foreach ($module->attendances->groupBy('trainee_id') as $traineeId => $attendances) {
            $trainee = $attendances->first()->trainee;
            $present = $attendances->where('status', 'present')->count();
            $late = $attendances->where('status', 'late')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $hours = $attendances->reduce(function ($carry, $record) {
                if ($record->entry_time && $record->exit_time) {
                    return $carry + \Carbon\Carbon::parse($record->exit_time)->diffInMinutes(\Carbon\Carbon::parse($record->entry_time)) / 60;
                }
                return $carry;
            }, 0);

            $traineeStats[] = [
                'trainee' => $trainee->name,
                'module' => $module->name,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'hours' => number_format($hours, 2),
            ];
        }
    }



    return view('trainer.reports', compact('traineeStats'));
}

public function export(Request $request)
{
    $trainerId = Auth::id();

    $query = Absence::with(['user', 'module'])->whereHas('module', function ($q) use ($trainerId) {
        $q->where('trainer_id', $trainerId);
    });

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('module_id')) {
        $query->where('module_id', $request->module_id);
    }

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    $absences = $query->get();

    $pdf = Pdf::loadView('trainer.absences.pdf', compact('absences'));

    return $pdf->download('trainer_absences.pdf');
}

public function exportAbsenceStats()
{
    $trainerId = Auth::id();
    return Excel::download(new TrainerAbsenceStatsExport($trainerId), 'trainer_absence_stats.xlsx');
}

public function showTraineeProfile($id)
{
    $trainer = auth()->user();

    // Ensure the trainee is enrolled in at least one of the trainer’s modules
    $trainee = User::where('id', $id)
        ->whereHas('modules', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->with(['modules', 'absences.module'])
        ->firstOrFail();

    $justified = $trainee->absences->where('justified', true)->count();
    $unjustified = $trainee->absences->where('justified', false)->count();
    $total = $trainee->absences->count();

    return view('trainer.trainees.profile', compact('trainee', 'justified', 'unjustified', 'total'));
}


}

