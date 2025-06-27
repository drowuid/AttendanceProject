<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response; // ✅ Import the Response facade
use Illuminate\Http\Request;
use PDF;

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

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export absences as CSV
     */
    public function exportAbsencesCsv()
    {
        $user = Auth::user();

        $absences = $user->absences()->with('module')->orderBy('date')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="absences.csv"',
        ];

        $columns = ['Date', 'Module', 'Reason', 'Justified'];

        $callback = function () use ($absences, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($absences as $absence) {
                fputcsv($file, [
                    $absence->date ? \Carbon\Carbon::parse($absence->date)->format('d/m/Y') : 'N/A',
                    optional($absence->module)->name ?? 'N/A',
                    $absence->reason ?? '-',
                    $absence->justified ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
