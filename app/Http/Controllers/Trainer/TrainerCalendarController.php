<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class TrainerCalendarController extends Controller
{
    public function index(Request $request)
    {
        // If it's an AJAX request, return JSON data for the calendar
        if ($request->expectsJson() || $request->ajax()) {
            $events = CalendarEvent::with('module')->get()->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->module->name,
                    'start' => $event->start,
                    'end' => $event->end,
                    'backgroundColor' => '#3788d8',
                    'borderColor' => '#3788d8',
                    'extendedProps' => [
                        'module_id' => $event->module_id,
                        'description' => $event->description ?? ''
                    ]
                ];
            });

            return response()->json($events);
        }

        // Otherwise, return the view
        $modules = Module::select('id', 'name', 'start_date', 'end_date')->get();
        return view('trainer.calendar.index', compact('modules'));
    }

    public function showCalendar()
    {
        $modules = Module::all();
        return view('calendar.index', compact('modules'));
    }
}
