<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                        'description' => $event->module->description ?? ''
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

    public function events()
{
    $events = CalendarEvent::with('module')->get();

    $formatted = $events->map(function($event) {
        return [
            'id' => $event->id,
            'title' => $event->module ? $event->module->name : 'No Module',
            'start' => $event->start,
            'end' => $event->end,
            'module_id' => $event->module_id,
        ];
    });

    return response()->json($formatted);
}

public function store(Request $request)
{
    $request->validate([
        'start' => 'required|date',
        'end' => 'nullable|date',
        'module_id' => 'required|exists:modules,id',
    ]);

    // Get the module to use its name as title
    $module = Module::findOrFail($request->module_id);

    $event = CalendarEvent::create([
        'title' => $module->name,
        'start' => $request->start,
        'end' => $request->end,
        'module_id' => $request->module_id,
    ]);

    return response()->json([
        'success' => true,
        'event' => [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'module_id' => $event->module_id,
        ],
    ]);
}

public function update(Request $request, CalendarEvent $event)
{
    $data = $request->validate([
        'start' => 'nullable|date',
        'end' => 'nullable|date',
        'module_id' => 'nullable|exists:modules,id',
    ]);

    $event->update(array_filter($data));

    return response()->json(['success' => true]);
}

public function destroy(CalendarEvent $event)
{
    $event->delete();
    return response()->json(['success' => true]);
}




}
