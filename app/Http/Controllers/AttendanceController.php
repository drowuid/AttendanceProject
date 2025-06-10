<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('trainee.attendance');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $pcName = gethostname();

        if ($request->status === 'present') {
            Attendance::create([
                'trainee_id' => $user->id,
                'date' => $now->toDateString(),
                'entry_time' => $now->toTimeString(),
                'status' => 'present',
                'reason' => $request->reason,
                'pc_name' => $pcName,
            ]);
        } elseif ($request->status === 'exit') {
            // Update today's entry
            $attendance = Attendance::where('trainee_id', $user->id)
                ->where('date', $now->toDateString())
                ->latest()
                ->first();

            if ($attendance) {
                $attendance->update([
                    'exit_time' => $now->toTimeString(),
                    'pc_name' => $pcName,
                    'reason' => $request->reason ?? $attendance->reason,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Attendance recorded.');
    }
}
