<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendancePin;
use App\Models\ConfirmedAttendance;
use Carbon\Carbon;
use Auth;

class TraineeAttendanceController extends Controller
{
    public function index()
    {
        $modules = Auth::user()->modules()->get();
        $pins = AttendancePin::where('expires_at', '>', now())->get();

        return view('trainee.attendance', compact('modules', 'pins'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'pin' => 'required|string',
        ]);

        $pin = AttendancePin::where('module_id', $request->module_id)
            ->where('pin', $request->pin)
            ->where('expires_at', '>', now())
            ->first();

        if (!$pin) {
            return back()->withErrors(['pin' => 'Invalid or expired PIN.']);
        }

        ConfirmedAttendance::updateOrCreate(
            [
                'trainee_id' => Auth::id(),
                'module_id' => $request->module_id,
                'date' => Carbon::today(),
            ],
            [
                'present' => true,
            ]
        );

        return back()->with('success', 'Attendance confirmed.');
    }

    public function justify(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'justification_file' => 'required|file|mimes:pdf,jpg,png,doc,docx',
        ]);

        $path = $request->file('justification_file')->store('justifications');

        ConfirmedAttendance::updateOrCreate(
            [
                'trainee_id' => Auth::id(),
                'module_id' => $request->module_id,
                'date' => Carbon::today(),
            ],
            [
                'present' => false,
                'justification_file' => $path,
            ]
        );

        return back()->with('success', 'Justification uploaded.');
    }
}
