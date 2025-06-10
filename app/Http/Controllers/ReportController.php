<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with('attendances.trainee');

        // Filter by date range if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $query->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end]);
        }

        // Optionally filter by module name or trainee if you want to extend here

        $modules = $query->get();

        return view('reports.index', compact('modules'));
    }
}
