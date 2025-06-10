<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trainee;
use App\Models\Attendance;
use Carbon\Carbon;

class MarkAbsentees extends Command
{
    protected $signature = 'attendance:mark-absentees';

    protected $description = 'Mark trainees absent if they have not logged entry today';

    public function handle()
    {
        $now = now();
        $today = $now->toDateString();

        $session = $now->hour < 13 ? 'morning' : 'afternoon';

        $trainees = Trainee::all();

        foreach ($trainees as $trainee) {
            $alreadyLogged = Attendance::where('trainee_id', $trainee->id)
                ->where('date', $today)
                ->where('session', $session)
                ->exists();

            if (!$alreadyLogged) {
                Attendance::create([
                    'trainee_id' => $trainee->id,
                    'status' => 'absent',
                    'session' => $session,
                    'date' => $today,
                    'pc_info' => 'system',
                ]);
            }
        }

        $this->info('Absentees marked for ' . $session . ' session.');
    }
}
