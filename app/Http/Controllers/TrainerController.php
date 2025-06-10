<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Trainee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $modules = Module::with('attendances.trainee')->get();

        return view('trainer.dashboard', compact('modules'));
    }
}

