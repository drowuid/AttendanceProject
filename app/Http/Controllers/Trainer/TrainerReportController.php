<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class TrainerReportController extends Controller
{
    public function index(Request $request)
    {
        $trainerId = Auth::id();

        $modules = Module::where('trainer_id', $trainerId)->get();

        $absences = Absence::with(['trainee', 'module'])
            ->whereHas('module', fn($q) => $q->where('trainer_id', $trainerId))
            ->when($request->module_id, fn($q) => $q->where('module_id', $request->module_id))
            ->when($request->start_date, fn($q) => $q->whereDate('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('date', '<=', $request->end_date))
            ->latest()
            ->paginate(10);

        return view('trainer.reports.index', compact('absences', 'modules'));
    }

public function exportPdf(Request $request)
{
    $trainerId = Auth::id();

    $query = Absence::with('trainee', 'module')
        ->whereHas('module', fn($q) => $q->where('trainer_id', $trainerId));

    if ($request->has('module_id')) {
    $moduleIds = $request->input('module_id');
    $query->whereIn('module_id', (array) $moduleIds);
}

    if ($request->filled('from_date')) {
        $query->whereDate('date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    $absences = $query->orderBy('date', 'desc')->get();

    $pdf = Pdf::loadView('trainer.reports.pdf', compact('absences'));

    return $pdf->download('absence_report.pdf');
}

public function exportAbsenceEmailSummary()
{
    $trainerId = Auth::id();

    $absences = \App\Models\Absence::with(['trainee', 'module'])
        ->whereHas('module', function($q) use ($trainerId) {
            $q->where('trainer_id', $trainerId);
        })
        ->get();



    $summary = [];
    $summary[] = "Trainer Absence Summary Report";
    $summary[] = "Generated on: " . now()->format('Y-m-d H:i');
    $summary[] = "----------------------------------------";

    $total = $absences->count();
    $justified = $absences->where('justified', true)->count();
    $unjustified = $total - $justified;

    $summary[] = "Total Absences: $total";
    $summary[] = "Justified: $justified";
    $summary[] = "Unjustified: $unjustified";
    $summary[] = "";

    $byModule = $absences->groupBy('module.name');
    foreach ($byModule as $module => $records) {
        $summary[] = "Module: $module";
        $summary[] = "- Absences: " . $records->count();
        $summary[] = "- Justified: " . $records->where('justified', true)->count();
        $summary[] = "- Unjustified: " . $records->where('justified', false)->count();
        $summary[] = "";
    }

    $filename = 'absence_summary_' . now()->format('Ymd_His') . '.txt';
    $content = implode(PHP_EOL, $summary);

    return response($content)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', "attachment; filename=\"$filename\"");
}


public function exportCsv(Request $request)
{
    $trainerId = Auth::id();

    $query = Absence::with(['trainee', 'module'])
        ->whereHas('module', fn($q) => $q->where('trainer_id', $trainerId));

    if ($request->filled('module_id')) {
        $query->where('module_id', $request->input('module_id'));
    }

    if ($request->filled('start_date')) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    $absences = $query->get();

    $filename = 'absence_report_' . now()->format('Ymd_His') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($absences) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Trainee', 'Module', 'Date', 'Reason', 'Justified']);

        foreach ($absences as $absence) {
            fputcsv($handle, [
                $absence->trainee->name ?? 'N/A',
                $absence->module->name ?? 'N/A',
                $absence->date,
                $absence->reason,
                $absence->justified ? 'Yes' : 'No'
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}


}
