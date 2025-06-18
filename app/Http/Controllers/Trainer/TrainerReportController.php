<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
}
