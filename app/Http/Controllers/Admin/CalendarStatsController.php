<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarStatsController extends Controller
{
    public function getAbsenceStatsByDay(Request $request)
    {
        $month = $request->input('month');

        if (!$month) {
            return response()->json(['error' => 'Month not specified'], 400);
        }

        $absences = Absence::select(DB::raw('DATE(date) as day'), DB::raw('count(*) as total'))
            ->where('date', 'like', $month . '%')
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy(DB::raw('DATE(date)'))
            ->pluck('total', 'day');

        return response()->json($absences);
    }
}
