@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Your Absence Statistics</h2>

    <p class="mb-4">Total Absences: <strong>{{ $totalAbsences }}</strong></p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h3 class="text-lg font-bold mb-2">Absences Per Module</h3>
            <canvas id="moduleChart"></canvas>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-2">Absences Per Month</h3>
            <canvas id="monthChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const moduleCtx = document.getElementById('moduleChart').getContext('2d');
    const monthCtx = document.getElementById('monthChart').getContext('2d');

    const moduleChart = new Chart(moduleCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($absencesByModule->keys()) !!},
            datasets: [{
                label: 'Absences',
                data: {!! json_encode($absencesByModule->values()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });

    const monthChart = new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($absencesPerMonth->keys()) !!},
            datasets: [{
                label: 'Absences per Month',
                data: {!! json_encode($absencesPerMonth->values()) !!},
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true
            }]
        }
    });
</script>
@endsection
