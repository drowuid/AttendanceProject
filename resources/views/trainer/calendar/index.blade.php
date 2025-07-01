@extends('layouts.app')

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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        editable: true,
        selectable: true,
        select: function(info) {
            const title = prompt('Event Title:');
            if (title) {
                const moduleId = prompt('Module ID (numeric):');
                axios.post("{{ route('trainer.calendar.store') }}", {
                    title: title,
                    module_id: moduleId,
                    start: info.startStr,
                    end: info.endStr
                }).then(response => {
                    if (response.data.success) {
                        calendar.refetchEvents();
                    }
                }).catch(error => {
                    console.error('Error creating event:', error);
                    alert('Error creating event. Please try again.');
                });
            }
        },
        eventResize: function(info) {
            axios.put("{{ url('trainer/calendar/events') }}/" + info.event.id, {
                title: info.event.title,
                module_id: info.event.extendedProps.module_id,
                start: info.event.start.toISOString(),
                end: info.event.end ? info.event.end.toISOString() : null
            }).then(response => {
                if (response.data.success) {
                    calendar.refetchEvents();
                }
            }).catch(error => {
                console.error('Error resizing event:', error);
                alert('Error updating event. Please try again.');
                info.revert();
            });
        },
        eventDrop: function(info) {
            axios.put("{{ url('trainer/calendar/events') }}/" + info.event.id, {
                title: info.event.title,
                module_id: info.event.extendedProps.module_id,
                start: info.event.start.toISOString(),
                end: info.event.end ? info.event.end.toISOString() : null
            }).then(response => {
                if (response.data.success) {
                    calendar.refetchEvents();
                }
            }).catch(error => {
                console.error('Error moving event:', error);
                alert('Error updating event. Please try again.');
                info.revert();
            });
        },
        eventClick: function(info) {
            if (confirm('Delete this event?')) {
                axios.delete("{{ url('trainer/calendar/events') }}/" + info.event.id)
                    .then(response => {
                        if (response.data.success) {
                            calendar.refetchEvents();
                        }
                    }).catch(error => {
                        console.error('Error deleting event:', error);
                        alert('Error deleting event. Please try again.');
                    });
            }
        },
        events: [
            @foreach($modules as $module)
            {
                id: "{{ $module->id }}",
                title: "{{ $module->name }}",
                start: "{{ $module->start_date }}",
                end: "{{ $module->end_date }}",
                extendedProps: {
                    module_id: "{{ $module->id }}"
                }
            },
            @endforeach
        ]
    });
    calendar.render();
});
</script>
@endsection
