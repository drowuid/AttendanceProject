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
        events: [
            @foreach($modules as $module)
            {
                title: "{{ $module->name }}",
                start: "{{ $module->start_date }}",
                end: "{{ $module->end_date }}"
            },
            @endforeach
        ]
    });
    calendar.render();
});
</script>
@endsection
