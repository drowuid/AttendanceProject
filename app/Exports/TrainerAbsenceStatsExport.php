<?php

namespace App\Exports;

use App\Models\Absence;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrainerAbsenceStatsExport implements FromView
{
    protected $trainerId;

    public function __construct($trainerId)
    {
        $this->trainerId = $trainerId;
    }

    public function view(): View
    {
        $absences = Absence::with(['attendance.trainee', 'module'])
            ->whereHas('module', function ($query) {
                $query->where('trainer_id', $this->trainerId);
            })
            ->whereNull('deleted_at')
            ->get();

        return view('exports.trainer_absence_stats', [
            'absences' => $absences,
        ]);
    }
}
