@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Users -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Total Users</h2>
        <p class="text-3xl font-bold">{{ $userCount }}</p>
    </div>

    <!-- Trainers -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Total Trainers</h2>
        <p class="text-3xl font-bold">{{ $trainerCount }}</p>
    </div>

    <!-- Trainees -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Total Trainees</h2>
        <p class="text-3xl font-bold">{{ $traineesCount }}</p>
    </div>

    <!-- Absences (from absences table) -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Total Absences (Records)</h2>
        <p class="text-3xl font-bold">{{ $absenceCount }}</p>
    </div>

    <!-- Absences (from attendances with status = absent) -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Total Absences (Marked)</h2>
        <p class="text-3xl font-bold">{{ $absencesCount }}</p>
    </div>

    <!-- Modules -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Modules</h2>
        <p class="text-3xl font-bold">{{ $moduleCount }}</p>
    </div>

    <!-- Courses -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Courses</h2>
        <p class="text-3xl font-bold">{{ $courseCount }}</p>
    </div>

    <!-- Active Sessions -->
    <div class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-2">Active Sessions</h2>
        <p class="text-3xl font-bold">{{ $activeSessions ?? 0 }}</p>
    </div>

</div>


    <!-- Absence Chart -->
    <div class="mt-10 bg-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-4">Absences per Month</h2>
        <canvas id="absenceChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('absenceChart').getContext('2d');
    const absenceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($absenceChartLabels) !!},
            datasets: [{
                label: 'Absences',
                data: {!! json_encode($absenceChartData) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
