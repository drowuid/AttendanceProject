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
                <a href="{{ route('trainee.dashboard') }}"
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
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: "{{ route('trainee.calendar.events') }}"
    });
    calendar.render();
});
</script>
@endsection
