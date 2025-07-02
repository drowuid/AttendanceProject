@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
        <div class="container mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Course Calendar</h1>
                    <a href="{{ route('trainer.dashboard') }}"
                        class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                        Back to Dashboard
                    </a>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-80">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Schedule Module</h2>
            <form id="eventForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="start" id="eventStart">
                <input type="hidden" name="end" id="eventEnd">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Module:</label>
                <select name="module_id" id="moduleSelect"
                    class="w-full border p-2 rounded dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="">Select a module</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelButton"
                        class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 transition">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View/Edit Event Modal -->
    <div id="viewEventModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-96">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Event Details</h2>
            <form id="viewEventForm">
                @csrf
                <input type="hidden" id="viewEventId">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Module:</label>
                <select name="module_id" id="viewModuleSelect"
                    class="w-full border p-2 rounded dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                    @endforeach
                </select>
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Start Date:</label>
                    <input type="text" id="viewStartDate"
                        class="w-full border p-2 rounded dark:bg-gray-900 dark:border-gray-700 dark:text-white" readonly>
                </div>
                <div class="mt-2">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">End Date:</label>
                    <input type="text" id="viewEndDate"
                        class="w-full border p-2 rounded dark:bg-gray-900 dark:border-gray-700 dark:text-white" readonly>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="deleteEventButton"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Delete</button>
                    <button type="button" id="closeViewModalButton"
                        class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 transition">Close</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('eventModal');
            const form = document.getElementById('eventForm');
            const startInput = document.getElementById('eventStart');
            const endInput = document.getElementById('eventEnd');
            const cancelButton = document.getElementById('cancelButton');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                selectable: true,
                editable: true,
                select: function(info) {
                    startInput.value = info.startStr;
                    endInput.value = info.endStr;
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch("{{ route('trainer.calendar.index') }}")
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    const viewModal = document.getElementById('viewEventModal');
                    document.getElementById('viewEventId').value = info.event.id;
                    document.getElementById('viewModuleSelect').value = info.event.extendedProps
                        .module_id;
                    document.getElementById('viewStartDate').value = info.event.start.toISOString()
                        .split('T')[0];
                    document.getElementById('viewEndDate').value = info.event.end ? info.event.end
                        .toISOString().split('T')[0] : '';
                    viewModal.classList.remove('hidden');
                    viewModal.style.display = 'flex';
                },
                eventDrop: function(info) {
                    fetch("{{ url('/trainer/calendar/events') }}/" + info.event.id, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: info.event.start.toISOString(),
                            end: info.event.end ? info.event.end.toISOString() : null
                        })
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update event');
                        }
                        return response.json();
                    }).then(data => {
                        if (data.success) {
                            calendar.refetchEvents();
                        } else {
                            throw new Error('Update failed');
                        }
                    }).catch(error => {
                        console.error('Error updating event:', error);
                        alert('Error updating event. Please try again.');
                        info.revert();
                    });
                },
                eventResize: function(info) {
                    fetch("{{ url('/trainer/calendar/events') }}/" + info.event.id, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: info.event.start.toISOString(),
                            end: info.event.end ? info.event.end.toISOString() : null
                        })
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update event');
                        }
                        return response.json();
                    }).then(data => {
                        if (data.success) {
                            calendar.refetchEvents();
                        } else {
                            throw new Error('Update failed');
                        }
                    }).catch(error => {
                        console.error('Error updating event:', error);
                        alert('Error updating event. Please try again.');
                        info.revert();
                    });
                }
            });

            calendar.render();

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                // Basic validation
                if (!formData.get('module_id')) {
                    alert('Please select a module');
                    return;
                }

                fetch("/trainer/calendar/events", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                }).then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Server response:', text);
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        modal.classList.add('hidden');
                        modal.style.display = 'none';
                        form.reset();

                        // Add the new event directly to the calendar
                        calendar.addEvent({
                            id: data.event.id,
                            title: data.event.title,
                            start: data.event.start,
                            end: data.event.end
                        });
                    } else {
                        throw new Error('Server returned success: false');
                    }
                }).catch(error => {
                    console.error('Error creating event:', error);
                    alert('Error creating event: ' + error.message);
                });
            });

            // Close the view modal
            document.getElementById('closeViewModalButton').addEventListener('click', () => {
                const viewModal = document.getElementById('viewEventModal');
                viewModal.classList.add('hidden');
                viewModal.style.display = 'none';
            });

            // Update the event
            document.getElementById('viewEventForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const eventId = document.getElementById('viewEventId').value;
                const moduleId = document.getElementById('viewModuleSelect').value;

                axios.put('/trainer/calendar/events/' + eventId, {
                    module_id: moduleId
                }).then(response => {
                    if (response.data.success) {
                        const viewModal = document.getElementById('viewEventModal');
                        viewModal.classList.add('hidden');
                        viewModal.style.display = 'none';
                        calendar.refetchEvents();
                    } else {
                        alert('Error updating event.');
                    }
                }).catch(error => {
                    console.error('Error updating event:', error);
                    alert('Error updating event.');
                });
            });

            // Delete the event
            document.getElementById('deleteEventButton').addEventListener('click', function() {
                const eventId = document.getElementById('viewEventId').value;

                if (confirm('Are you sure you want to delete this event?')) {
                    fetch('/trainer/calendar/events/' + eventId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        if (response.ok) {
                            const viewModal = document.getElementById('viewEventModal');
                            viewModal.classList.add('hidden');
                            viewModal.style.display = 'none';
                            calendar.refetchEvents();
                        } else {
                            alert('Error deleting event.');
                        }
                    }).catch(error => {
                        console.error('Error deleting event:', error);
                        alert('Error deleting event.');
                    });
                }
            });


            cancelButton.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                form.reset();
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                    form.reset();
                }
            });
        });
    </script>
@endsection
