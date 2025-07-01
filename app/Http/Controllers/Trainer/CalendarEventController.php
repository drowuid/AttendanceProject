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
                'extendedProps' => [
                    'module_id' => $event->module_id
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

            $event = CalendarEvent::create([
                'module_id' => $validated['module_id'],
                'title' => $module->name, // Set the title
                'start' => $validated['start'],
                'end' => $validated['end'] ?? $validated['start'],
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
