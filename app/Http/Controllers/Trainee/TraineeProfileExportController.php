<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TraineeProfileExportController extends Controller
{
    /**
     * Export profile summary as PDF
     */
    public function exportProfilePdf()
    {
        $user = Auth::user();
        $modules = $user->modules()->with('trainer')->get();
        $absences = $user->absences()->with('module')->orderBy('date', 'desc')->get();

        $pdf = PDF::loadView('trainee.exports.profile_pdf', compact('user', 'modules', 'absences'));

        return $pdf->download('profile_summary.pdf');
    }

    /**
     * Export assigned modules as CSV
     */
    public function exportModulesCsv()
    {
        $user = Auth::user();
        $modules = $user->modules()->with('trainer')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="modules.csv"',
        ];

        $callback = function () use ($modules) {
            $handle = fopen('php://output', 'w');

            // CSV headers
            fputcsv($handle, [
                'Module Name',
                'Trainer',
                'Hours',
                'Start Date',
                'End Date',
            ]);

            foreach ($modules as $module) {
                fputcsv($handle, [
                    $module->name,
                    optional($module->trainer)->name ?? 'N/A',
                    $module->hours,
                    $module->start_date,
                    $module->end_date,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
