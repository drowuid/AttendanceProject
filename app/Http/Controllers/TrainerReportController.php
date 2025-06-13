<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainerReportController extends Controller
{
    public function index()
{
    $modules = auth()->user()->modules; // assuming relationship defined
    return view('trainer.reports.index', compact('modules'));
}

public function show(Module $module)
{
    // Only allow access if module belongs to this trainer
    if ($module->trainer_id !== auth()->id()) {
        abort(403);
    }

    $trainees = $module->trainees()->with('attendances')->get();

    return view('trainer.reports.show', compact('module', 'trainees'));
}

}
