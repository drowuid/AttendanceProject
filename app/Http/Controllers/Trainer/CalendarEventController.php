<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;

class CalendarEventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $event = CalendarEvent::create($validated);

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function update(Request $request, CalendarEvent $event)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $event->update($validated);

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function destroy(CalendarEvent $event)
    {
        $event->delete();

        return response()->json(['success' => true]);
    }
}
