<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Trainee;
use App\Models\Attendance;
use Illuminate\Http\Request;

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

}

