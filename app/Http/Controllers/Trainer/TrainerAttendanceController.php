<?php

namespace App\Http\Controllers\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendancePin;
use App\Models\Module;
use Carbon\Carbon;

class TrainerAttendanceController extends Controller
{
    public function generatePinManual(Request $request)
    {
        $trainerId = auth()->id();

        // Find all modules assigned to this trainer
        // You might need to adjust this query based on your actual database structure
        $modules = Module::where('trainer_id', $trainerId)->get();

        if ($modules->isEmpty()) {
            return back()->with('error', 'No modules assigned to you.');
        }

        $generatedPins = [];

        foreach ($modules as $module) {
            // Generate random 4-digit PIN
            $pin = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

            // First, delete any existing active pins for this module
            AttendancePin::where('module_id', $module->id)
                ->where('expires_at', '>', now())
                ->delete();

            // Create new pin
            $attendancePin = AttendancePin::create([
                'module_id' => $module->id,
                'pin' => $pin,
                'expires_at' => now()->addMinutes(15), // 15 minutes expiry
            ]);

            $generatedPins[] = [
                'module' => $module->name,
                'pin' => $pin,
                'expires_at' => $attendancePin->expires_at
            ];
        }

        return back()->with('success', 'Attendance PINs generated successfully for all your modules.')
                    ->with('generated_pins', $generatedPins);
    }

    public function generatePinForModule(Request $request, $moduleId)
    {
        $trainerId = auth()->id();

        // Verify the module belongs to this trainer
        $module = Module::where('id', $moduleId)
                        ->where('trainer_id', $trainerId)
                        ->first();

        if (!$module) {
            return back()->with('error', 'Module not found or you do not have permission to generate PINs for this module.');
        }

        // Generate random 4-digit PIN
        $pin = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Delete any existing active pins for this module
        AttendancePin::where('module_id', $module->id)
            ->where('expires_at', '>', now())
            ->delete();

        // Create new pin
        $attendancePin = AttendancePin::create([
            'module_id' => $module->id,
            'pin' => $pin,
            'expires_at' => now()->addMinutes(15), // 15 minutes expiry
        ]);

        return back()->with('success', "PIN generated for {$module->name}: {$pin}")
                    ->with('generated_pin', [
                        'module' => $module->name,
                        'pin' => $pin,
                        'expires_at' => $attendancePin->expires_at
                    ]);
    }

    public function getActivePins()
    {
        $trainerId = auth()->id();

        $activePins = AttendancePin::with('module')
            ->whereHas('module', function($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'desc')
            ->get();

        return response()->json($activePins);
    }

    public function deactivatePin($pinId)
    {
        $trainerId = auth()->id();

        $pin = AttendancePin::with('module')
            ->whereHas('module', function($query) use ($trainerId) {
                $query->where('trainer_id', $trainerId);
            })
            ->where('id', $pinId)
            ->first();

        if (!$pin) {
            return back()->with('error', 'PIN not found or you do not have permission to deactivate it.');
        }

        $pin->update(['expires_at' => now()]);

        return back()->with('success', 'PIN deactivated successfully.');
    }
}
