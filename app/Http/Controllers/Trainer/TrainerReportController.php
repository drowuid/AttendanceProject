<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

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
}
