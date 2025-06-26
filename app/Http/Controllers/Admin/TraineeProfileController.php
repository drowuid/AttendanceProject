<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;


class TraineeProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with(['modules.trainer', 'absences.module'])->findOrFail($id);

        $sort = request('sort', 'desc');

        $absences = Absence::with('module')
            ->where('user_id', $user->id)
            ->orderBy('date', $sort)
            ->get();

        $total = $absences->count();
        $justified = $absences->where('justified', true)->count();
        $unjustified = $absences->where('justified', false)->count();

        // ✅ Only modules assigned to this trainee
        $modules = $user->modules ?? collect();

        if (request()->has('module') && request('module') !== '') {
            $modules = $modules->filter(function ($mod) {
                return str_contains(strtolower($mod->name), strtolower(request('module')));
            });
        }

        // Total possible attendance days (from module durations)
        $expectedDays = $modules->reduce(function ($carry, $module) {
            $start = \Carbon\Carbon::parse($module->start_date);
            $end = \Carbon\Carbon::parse($module->end_date);
            $days = $start->diffInWeekdays($end) + 1; // Monday–Friday only
            return $carry + $days;
        }, 0);

        // Avoid division by zero
        $attendanceRate = $expectedDays > 0
            ? round((($expectedDays - $total) / $expectedDays) * 100)
            : null;

            $availableModules = \App\Models\Module::with('trainer')->get();

        return view('admin.trainees.profile', compact(
    'user', 'absences', 'total', 'justified', 'unjustified', 'modules', 'availableModules', 'attendanceRate'
));

    }

    public function exportProfilePdf($id)
{
    $trainee = User::with(['course', 'modules', 'absences.module'])
        ->where('role', 'trainee')
        ->findOrFail($id);

    $pdf = Pdf::loadView('admin.trainees.export-profile-pdf', compact('trainee'));

    return $pdf->download("Trainee_Profile_{$trainee->name}.pdf");
}

public function showProfile($id)
{
    $user = User::with(['course', 'modules'])->findOrFail($id);

    $absences = Absence::with('module')
        ->where('user_id', $id)
        ->when(request('sort') === 'asc', fn($q) => $q->orderBy('date', 'asc'))
        ->when(request('sort') === 'desc', fn($q) => $q->orderBy('date', 'desc'))
        ->get();

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $absences->where('justified', false)->count();

    // Only show modules assigned to this trainee
    $modules = $user->modules ?? collect();

    // Total possible attendance days (from module durations)
    $expectedDays = $modules->reduce(function ($carry, $module) {
        $start = \Carbon\Carbon::parse($module->start_date);
        $end = \Carbon\Carbon::parse($module->end_date);
        $days = $start->diffInWeekdays($end) + 1; // Monday–Friday only
        return $carry + $days;
    }, 0);

    // Avoid division by zero
    $attendanceRate = $expectedDays > 0
        ? round((($expectedDays - $total) / $expectedDays) * 100)
        : null;

        $availableModules = \App\Models\Module::with('trainer')->get();

    return view('admin.trainees.profile', compact(
    'user', 'absences', 'total', 'justified', 'unjustified', 'modules', 'availableModules', 'attendanceRate'
));

}


public function exportModulesCsv($id)
{
    $user = \App\Models\User::with(['modules.trainer'])->findOrFail($id);

    $modules = $user->modules;

    if ($modules->isEmpty()) {
        return redirect()->back()->with('error', 'No modules to export.');
    }

    $csvData = [];
    $csvData[] = ['Module Name', 'Trainer', 'Hours', 'Start Date', 'End Date'];

    foreach ($modules as $module) {
        $csvData[] = [
            $module->name,
            $module->trainer->name ?? 'N/A',
            $module->hours,
            \Carbon\Carbon::parse($module->start_date)->format('d/m/Y'),
            \Carbon\Carbon::parse($module->end_date)->format('d/m/Y'),
        ];
    }

    $filename = 'Assigned_Modules_' . $user->name . '.csv';

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

public function assignModules($id)
{
    $user = User::where('role', 'trainee')->findOrFail($id);

    $moduleIds = request()->input('modules', []);

    // Sync assigned modules
    $user->modules()->sync($moduleIds);

    return redirect()->route('admin.admin.trainees.profile', $id)
        ->with('success', 'Modules updated successfully.');
}
}
