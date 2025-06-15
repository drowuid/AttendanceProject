@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Trainer Absence Calendar</h2>

    <div id="calendar" class="bg-white shadow rounded p-4"></div>
</div>

{{-- FullCalendar CSS --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">

{{-- FullCalendar JS --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            events: {!! json_encode($events) !!},
            headerToolbar: {
                left: 'prev,next today',
                center: 'Trainer Absence Calendar',
                right: 'dayGridMonth,listWeek'
            },
            eventClick: function(info) {
                if (info.event.url) {
                    window.open(info.event.url, "_blank");
                    info.jsEvent.preventDefault();
                }
            }
        });

        calendar.render();
    });
</script>
@endsection