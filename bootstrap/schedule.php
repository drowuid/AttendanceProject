
<?php

use Illuminate\Foundation\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

return function (Schedule $schedule) {
    $schedule->command('attendance:record-absences')->dailyAt('18:00');
    Schedule::command('attendance:mark-absentees')->dailyAt('13:01');
    Schedule::command('attendance:mark-absentees')->dailyAt('18:01');
};

