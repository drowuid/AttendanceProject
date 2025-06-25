<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    public function exportAbsencesByReason()
    {
        $data = \App\Models\Absence::select('reason', DB::raw('count(*) as total'))
            ->groupBy('reason')
            ->orderByDesc('total')
            ->get();

        $filename = 'absences_by_reason_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Reason', 'Total']);
            foreach ($data as $row) {
                fputcsv($handle, [$row->reason ?? 'Unknown', $row->total]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportJustifiedVsUnjustified(): StreamedResponse
{
    $data = \App\Models\Absence::select('justified', DB::raw('count(*) as total'))
        ->groupBy('justified')
        ->pluck('total', 'justified')
        ->toArray();

    // Normalize data
    $justifiedAbsences = [
        'Justified' => $data[1] ?? 0,
        'Unjustified' => $data[0] ?? 0,
    ];

    $filename = 'justified_vs_unjustified_' . now()->format('Y-m-d_H-i-s') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($justifiedAbsences) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Type', 'Total']);
        foreach ($justifiedAbsences as $type => $total) {
            fputcsv($handle, [$type, $total]);
        }
        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}

public function exportWeeklyAbsences(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    $weeklyAbsences = \App\Models\Absence::select(
        DB::raw('DATE(date) as day'),
        DB::raw('COUNT(*) as total')
    )
        ->whereDate('date', '>=', now()->subDays(6))
        ->groupBy('day')
        ->orderBy('day', 'asc')
        ->pluck('total', 'day')
        ->toArray();

    $allDays = collect(range(0, 6))->mapWithKeys(function ($i) {
        return [now()->subDays(6 - $i)->format('Y-m-d') => 0];
    });

    $weeklyAbsencesFilled = array_merge($allDays->toArray(), $weeklyAbsences);

    $filename = 'weekly_absences_' . now()->format('Y-m-d_H-i-s') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($weeklyAbsencesFilled) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Day', 'Total']);
        foreach ($weeklyAbsencesFilled as $day => $total) {
            fputcsv($handle, [$day, $total]);
        }
        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}


}
