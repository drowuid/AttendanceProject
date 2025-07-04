<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\AttendancePin;
use Carbon\Carbon;

class GenerateAttendancePins extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-attendance-pins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            AttendancePin::updateOrCreate(
                ['module_id' => $module->id],
                [
                    'pin' => rand(1000, 99999),
                    'expires_at' => Carbon::now()->addMinutes(3),
                ]
            );
        }

        $this->info('Attendance PINs generated successfully.');
    }
}
