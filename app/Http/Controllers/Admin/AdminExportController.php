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
}
