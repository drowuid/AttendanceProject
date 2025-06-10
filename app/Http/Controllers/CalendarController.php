<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class CalendarController extends Controller
{
    public function index()
    {
        $modules = Module::orderBy('start_date')->get();

        return view('calendar.index', compact('modules'));
    }
}
