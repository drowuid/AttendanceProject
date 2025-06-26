<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class TrainerProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with(['modules.trainees', 'absences.module'])->findOrFail($id);

        $modules = $user->modules ?? collect();

        $absences = Absence::with(['module'])
            ->where('logged_by', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.trainers.profile', compact('user', 'modules', 'absences'));
    }

    public function exportModulesCsv($id)
    {
        $user = User::with(['modules'])->findOrFail($id);
        $modules = $user->modules;

        $csvData = [['Module Name', 'Hours', 'Start Date', 'End Date']];
        foreach ($modules as $module) {
            $csvData[] = [
                $module->name,
                $module->hours,
                \Carbon\Carbon::parse($module->start_date)->format('d/m/Y'),
                \Carbon\Carbon::parse($module->end_date)->format('d/m/Y'),
            ];
        }

        $filename = 'Trainer_Modules_' . $user->name . '.csv';
        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function exportProfilePdf($id)
{
    $user = User::with(['modules', 'modules.absences.user', 'modules.absences.module'])->findOrFail($id);

    $modules = $user->modules ?? collect();

    $absences = collect();
    foreach ($modules as $module) {
        foreach ($module->absences as $absence) {
            $absences->push($absence);
        }
    }

    $absences = $absences->sortByDesc('date');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.trainers.export-profile-pdf', compact('user', 'modules', 'absences'));

    return $pdf->download('Trainer_Profile_' . $user->name . '.pdf');
}



}
