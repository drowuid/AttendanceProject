<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Module;

class CalendarEventController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::with('module')->get()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->module->name,
                'start' => $event->start,
                'end' => $event->end,
                'allDay' => true,
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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'module_id' => 'required|exists:modules,id',
                'start' => 'required|date',
                'end' => 'nullable|date',
            ]);

            // Get the module to use its name as title
            $module = Module::findOrFail($validated['module_id']);

            // Format dates properly - for single day events, don't extend to next day
            $startDate = \Carbon\Carbon::parse($validated['start']);

            // If it's a day selection (no time), make it a single day event
            if (strlen($validated['start']) === 10) { // Format: YYYY-MM-DD
                $endDate = $startDate->copy(); // Same day, not extending to next day
            } else {
                $endDate = $validated['end'] ? \Carbon\Carbon::parse($validated['end']) : $startDate->copy();
            }

            $event = CalendarEvent::create([
                'module_id' => $validated['module_id'],
                'title' => $module->name,
                'description' => 'Module: ' . $module->name,
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ]);

            // Load the module relationship for the response
            $event->load('module');

            return response()->json([
                'success' => true,
                'event' => [
                    'id' => $event->id,
                    'title' => $event->module->name,
                    'start' => $event->start,
                    'end' => $event->end,
                    'backgroundColor' => '#3788d8',
                    'borderColor' => '#3788d8',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating calendar event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, CalendarEvent $event)
    {
        try {
            $validated = $request->validate([
                'start' => 'required|date',
                'end' => 'nullable|date',
            ]);

            $event->update([
                'start' => $validated['start'],
                'end' => $validated['end']
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Error updating calendar event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(CalendarEvent $event)
    {
        try {
            $event->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting calendar event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting event: ' . $e->getMessage()
            ], 500);
        }
    }
}
