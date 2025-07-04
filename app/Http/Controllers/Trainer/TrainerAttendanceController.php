<?php

namespace App\Http\Controllers\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendancePin;
use App\Models\Module;
use Carbon\Carbon;

class TrainerAttendanceController extends Controller
{
    /**
     * Generate PINs for modules scheduled today
     */
    public function generatePinManual(Request $request)
    {
        $trainerId = auth()->id();

        // Get modules for this trainer that have schedule today
        $todayModules = Module::where('trainer_id', $trainerId)
            ->whereHas('schedules', function ($query) {
                $query->whereDate('scheduled_date', Carbon::today());
            })
            ->get();

        if ($todayModules->isEmpty()) {
            return back()->with('error', 'No modules scheduled for today.');
        }

        $generatedPins = [];

        foreach ($todayModules as $module) {
            $pin = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

            AttendancePin::where('module_id', $module->id)
                ->where('expires_at', '>', now())
                ->delete();

            $attendancePin = AttendancePin::create([
                'module_id' => $module->id,
                'pin' => $pin,
                'expires_at' => now()->addMinutes(15),
            ]);

            $generatedPins[] = [
                'module' => $module->name,
                'pin' => $pin,
                'expires_at' => $attendancePin->expires_at,
            ];
        }

        return back()
            ->with('success', 'Attendance PINs generated for today\'s modules.')
            ->with('generated_pins', $generatedPins);
    }

    /**
     * Generate PIN for a single module scheduled today
     */
    public function generatePinForModule(Request $request, $moduleId)
    {
        $trainerId = auth()->id();

        $module = Module::where('id', $moduleId)
            ->where('trainer_id', $trainerId)
            ->whereHas('schedules', function ($query) {
                $query->whereDate('scheduled_date', Carbon::today());
            })
            ->first();

        if (!$module) {
            return back()->with('error', 'Module not found or not scheduled for today.');
        }

        $pin = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        AttendancePin::where('module_id', $module->id)
            ->where('expires_at', '>', now())
            ->delete();

        AttendancePin::create([
            'module_id' => $module->id,
            'pin' => $pin,
            'expires_at' => now()->addMinutes(15),
        ]);

        return back()->with('success', "PIN generated for '{$module->name}': {$pin}");
    }

    /**
     * Get active pins for today
     */
    public function getActivePins()
    {
        $trainerId = auth()->id();

        $activePins = AttendancePin::with('module')
            ->whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId)
                      ->whereHas('schedules', function ($q) {
                          $q->whereDate('scheduled_date', Carbon::today());
                      });
            })
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'desc')
            ->get();

        return response()->json($activePins);
    }

    /**
     * Deactivate a PIN
     */
    public function deactivatePin($pinId)
    {
        $trainerId = auth()->id();

        $pin = AttendancePin::with('module')
            ->whereHas('module', function ($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->where('id', $pinId)
            ->first();

        if (!$pin) {
            return back()->with('error', 'PIN not found or you do not have permission.');
        }

        $pin->update(['expires_at' => now()]);

        return back()->with('success', 'PIN deactivated successfully.');
    }
}
