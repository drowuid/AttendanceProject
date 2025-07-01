<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class TrainerCalendarController extends Controller
{
    public function index()
    {
        $modules = Module::select('id', 'name', 'start_date', 'end_date')->get();

        return view('trainer.calendar.index', compact('modules'));
    }
}
