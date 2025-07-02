<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;

class TraineeCalendarController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::with('module')->get();

        return view('trainee.calendar.index', [
            'events' => $events
        ]);
    }

    public function events()
    {
        $events = CalendarEvent::with('module')->get();

        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->module ? $event->module->name : 'Event',
                'start' => $event->start,
                'end' => $event->end,
            ];
        }));
    }
}
