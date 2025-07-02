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

    <!-- Schedule Modal -->
    <div id="eventModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-80">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Schedule Module</h2>
            <form id="eventForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="start" id="eventStart">
                <input type="hidden" name="end" id="eventEnd">
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Module:</label>
                <select name="module_id" id="moduleSelect"
                    class="w-full border p-2 rounded dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
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

    <!-- View/Edit Modal -->
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('eventModal');
            const form = document.getElementById('eventForm');
            const startInput = document.getElementById('eventStart');
            const endInput = document.getElementById('eventEnd');
            const cancelButton = document.getElementById('cancelButton');

            // Set up axios defaults
            axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
            axios.defaults.headers.common['Accept'] = 'application/json';

            // Helper function to format date properly for local timezone
            function formatDateTimeLocal(date, time) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}T${time}:00`;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventDidMount: function(info) {
    if (info.event.extendedProps.description) {
        const tooltip = document.createElement('div');
        tooltip.innerHTML = `<strong>${info.event.title}</strong><br>${info.event.extendedProps.description}`;
        tooltip.style.position = 'absolute';
        tooltip.style.zIndex = '10001';
        tooltip.style.background = '#333';
        tooltip.style.color = '#fff';
        tooltip.style.padding = '5px 8px';
        tooltip.style.borderRadius = '4px';
        tooltip.style.fontSize = '12px';
        tooltip.style.display = 'none';

        document.body.appendChild(tooltip);

        info.el.addEventListener('mouseenter', function(e) {
            tooltip.style.left = e.pageX + 'px';
            tooltip.style.top = e.pageY + 'px';
            tooltip.style.display = 'block';
        });

        info.el.addEventListener('mousemove', function(e) {
            tooltip.style.left = e.pageX + 'px';
            tooltip.style.top = e.pageY + 'px';
        });

        info.el.addEventListener('mouseleave', function() {
            tooltip.style.display = 'none';
        });
    }
},
                selectable: true,
                editable: true,
                slotMinTime: '08:00:00',
                slotMaxTime: '18:00:00',
                businessHours: {
                    startTime: '08:00',
                    endTime: '18:00'
                },
                events: "{{ route('trainer.calendar.events') }}",
                select: function(info) {
                    // For day grid view (month view), set default times
                    if (info.allDay) {
                        // Use the actual selected date without timezone conversion issues
                        const selectedDate = new Date(info.start);
                        // Adjust for timezone offset to get local date
                        selectedDate.setTime(selectedDate.getTime() - selectedDate.getTimezoneOffset() * 60000);

                        startInput.value = formatDateTimeLocal(selectedDate, '08:00');
                        endInput.value = formatDateTimeLocal(selectedDate, '18:00');
                    } else {
                        // For time grid views, use selected times but validate
                        const start = new Date(info.start);
                        const end = new Date(info.end);

                        // Check if same day
                        if (start.toDateString() !== end.toDateString()) {
                            alert('Modules cannot extend to the next day.');
                            return;
                        }

                        // Check time constraints
                        if (start.getHours() < 8) {
                            alert('Modules cannot start before 8AM.');
                            return;
                        }

                        if (end.getHours() > 18 || (end.getHours() === 18 && end.getMinutes() > 0)) {
                            alert('Modules cannot end after 6PM.');
                            return;
                        }

                        startInput.value = info.startStr;
                        endInput.value = info.endStr;
                    }

                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                },
                eventClick: function(info) {
                    const viewModal = document.getElementById('viewEventModal');
                    document.getElementById('viewEventId').value = info.event.id;
                    document.getElementById('viewModuleSelect').value = info.event.extendedProps
                        .module_id;

                    // Format dates properly for display using local date
                    const startDate = new Date(info.event.start);
                    const endDate = info.event.end ? new Date(info.event.end) : startDate;

                    document.getElementById('viewStartDate').value = startDate.toLocaleDateString();
                    document.getElementById('viewEndDate').value = endDate.toLocaleDateString();

                    viewModal.classList.remove('hidden');
                    viewModal.style.display = 'flex';
                },
                eventDrop: function(info) {
                    // Validate time constraints on drag
                    const start = new Date(info.event.start);
                    const end = new Date(info.event.end);

                    // Check if same day
                    if (start.toDateString() !== end.toDateString()) {
                        alert('Modules cannot extend to the next day.');
                        info.revert();
                        return;
                    }

                    // Check time constraints
                    if (start.getHours() < 8) {
                        alert('Modules cannot start before 8AM.');
                        info.revert();
                        return;
                    }

                    if (end.getHours() > 18 || (end.getHours() === 18 && end.getMinutes() > 0)) {
                        alert('Modules cannot end after 6PM.');
                        info.revert();
                        return;
                    }

                    axios.put('/trainer/calendar/events/' + info.event.id, {
                        start: info.event.start.toISOString(),
                        end: info.event.end ? info.event.end.toISOString() : null
                    }).then(() => {
                        calendar.refetchEvents();
                    }).catch((error) => {
                        console.error('Error updating event:', error);
                        alert('Error updating event: ' + (error.response?.data?.message || error.message));
                        info.revert();
                    });
                },
                eventResize: function(info) {
                    // Validate time constraints on resize
                    const start = new Date(info.event.start);
                    const end = new Date(info.event.end);

                    // Check if same day
                    if (start.toDateString() !== end.toDateString()) {
                        alert('Modules cannot extend to the next day.');
                        info.revert();
                        return;
                    }

                    // Check time constraints
                    if (start.getHours() < 8) {
                        alert('Modules cannot start before 8AM.');
                        info.revert();
                        return;
                    }

                    if (end.getHours() > 18 || (end.getHours() === 18 && end.getMinutes() > 0)) {
                        alert('Modules cannot end after 6PM.');
                        info.revert();
                        return;
                    }

                    axios.put('/trainer/calendar/events/' + info.event.id, {
                        start: info.event.start.toISOString(),
                        end: info.event.end ? info.event.end.toISOString() : null
                    }).then(() => {
                        calendar.refetchEvents();
                    }).catch((error) => {
                        console.error('Error updating event:', error);
                        alert('Error updating event: ' + (error.response?.data?.message || error.message));
                        info.revert();
                    });
                }
            });

            calendar.render();

            // Form submission for creating new events
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const moduleId = document.getElementById('moduleSelect').value;
                const start = startInput.value;
                const end = endInput.value;

                // Validate required fields
                if (!moduleId) {
                    alert('Please select a module');
                    return;
                }

                // Prepare data for submission
                const eventData = {
                    module_id: moduleId,
                    start: start,
                    end: end
                };

                // Validate time constraints
                const startDate = new Date(start);
                const endDate = new Date(end);

                // Check if same day
                if (startDate.toDateString() !== endDate.toDateString()) {
                    alert('Modules cannot extend to the next day.');
                    return;
                }

                // Check time constraints
                if (startDate.getHours() < 8) {
                    alert('Modules cannot start before 8AM.');
                    return;
                }

                if (endDate.getHours() > 18 || (endDate.getHours() === 18 && endDate.getMinutes() > 0)) {
                    alert('Modules cannot end after 6PM.');
                    return;
                }

                console.log('Submitting event data:', eventData);

                // Submit using axios for consistency
                axios.post("{{ route('trainer.calendar.events') }}", eventData)
                    .then(response => {
                        console.log('Event created successfully:', response.data);

                        // Close modal and reset form
                        modal.classList.add('hidden');
                        modal.style.display = 'none';
                        form.reset();

                        // Refresh calendar events
                        calendar.refetchEvents();

                        // Show success message
                        alert('Event created successfully!');
                    })
                    .catch(error => {
                        console.error('Error creating event:', error);

                        let errorMessage = 'Error creating event.';

                        if (error.response) {
                            // Server responded with error status
                            console.error('Response data:', error.response.data);
                            console.error('Response status:', error.response.status);

                            if (error.response.data?.message) {
                                errorMessage = error.response.data.message;
                            } else if (error.response.data?.errors) {
                                const errors = Object.values(error.response.data.errors).flat();
                                errorMessage = errors.join(', ');
                            } else if (typeof error.response.data === 'string') {
                                errorMessage = error.response.data;
                            }
                        } else if (error.request) {
                            // Request was made but no response received
                            console.error('No response received:', error.request);
                            errorMessage = 'No response from server. Please check your connection.';
                        } else {
                            // Something else happened
                            console.error('Error message:', error.message);
                            errorMessage = error.message;
                        }

                        alert(errorMessage);
                    });
            });

            // Close view modal button
            document.getElementById('closeViewModalButton').addEventListener('click', () => {
                document.getElementById('viewEventModal').classList.add('hidden');
                document.getElementById('viewEventModal').style.display = 'none';
            });

            // Update event form submission
            document.getElementById('viewEventForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const eventId = document.getElementById('viewEventId').value;
                const moduleId = document.getElementById('viewModuleSelect').value;

                axios.put('/trainer/calendar/events/' + eventId, {
                    module_id: moduleId
                }).then((response) => {
                    console.log('Event updated successfully:', response.data);
                    document.getElementById('viewEventModal').classList.add('hidden');
                    document.getElementById('viewEventModal').style.display = 'none';
                    calendar.refetchEvents();
                    alert('Event updated successfully!');
                }).catch((error) => {
                    console.error('Error updating event:', error);
                    let errorMessage = 'Error updating event.';
                    if (error.response?.data?.message) {
                        errorMessage = error.response.data.message;
                    }
                    alert(errorMessage);
                });
            });

            // Delete event button
            document.getElementById('deleteEventButton').addEventListener('click', function() {
                const eventId = document.getElementById('viewEventId').value;
                if (confirm('Are you sure you want to delete this event?')) {
                    axios.delete('/trainer/calendar/events/' + eventId)
                        .then((response) => {
                            console.log('Event deleted successfully:', response.data);
                            document.getElementById('viewEventModal').classList.add('hidden');
                            document.getElementById('viewEventModal').style.display = 'none';
                            calendar.refetchEvents();
                            alert('Event deleted successfully!');
                        }).catch((error) => {
                            console.error('Error deleting event:', error);
                            let errorMessage = 'Error deleting event.';
                            if (error.response?.data?.message) {
                                errorMessage = error.response.data.message;
                            }
                            alert(errorMessage);
                        });
                }
            });

            // Cancel button
            cancelButton.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                form.reset();
            });
        });
    </script>
@endsection
