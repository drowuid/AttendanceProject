<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\Trainee;
use App\Models\Attendance;
use Carbon\Carbon;

class RecordDailyAbsences extends Command
{
    protected $signature = 'attendance:record-absences';
    protected $description = 'Mark trainees absent if they did not register attendance today';

    public function handle()
    {
        $today = Carbon::today();

        $modulesToday = Module::whereDate('start_date', '<=', $today)
                              ->whereDate('end_date', '>=', $today)
                              ->get();

        foreach ($modulesToday as $module) {
            $trainees = $module->trainees; // Assumes relationship exists

            foreach ($trainees as $trainee) {
                $hasAttendance = Attendance::where('trainee_id', $trainee->id)
                                           ->whereDate('date', $today)
                                           ->exists();

                if (!$hasAttendance) {
                    Attendance::create([
                        'trainee_id' => $trainee->id,
                        'date' => $today->toDateString(),
                        'status' => 'absent',
                    ]);

                    $this->info("Marked absent: {$trainee->name}");
                }
            }
        }

        return 0;
    }
}
