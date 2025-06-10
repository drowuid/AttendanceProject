<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Module;
use App\Models\Trainee;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showForm()
    {
        $modules = Module::all();
        return view('attendance.form', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|in:entry,exit',
        ]);

        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();
        $hostname = gethostname();

        $attendance = Attendance::firstOrNew([
            'trainee_id' => $request->trainee_id,
            'module_id' => $request->module_id,
            'date' => $date,
        ]);

        if ($request->type === 'entry') {
            $attendance->entry_time = $time;

            // Mark as late if after 09:15 (example)
            $attendance->status = $now->gt(Carbon::createFromTime(9, 15)) ? 'late' : 'present';
        } else {
            $attendance->exit_time = $time;
        }

        $attendance->pc_identifier = $hostname;
        $attendance->reason = $request->reason ?? null;
        $attendance->save();

        return back()->with('success', 'Attendance logged.');
    }
}

