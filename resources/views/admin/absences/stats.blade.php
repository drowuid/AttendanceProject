@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Absence Statistics</h2>

    <p class="mb-4">Total Absences: <strong>{{ $totalAbsences }}</strong></p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div>
            <h3 class="text-lg font-bold mb-2">Absences Per Module</h3>
            <canvas id="moduleChart"></canvas>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-2">Absences Per Month</h3>
            <canvas id="monthChart"></canvas>
        </div>
    </div>

    {{-- Detailed breakdown per trainee per module --}}
    <h2 class="text-xl font-semibold mb-4">Detailed Absence Breakdown by Module & Trainee</h2>

    @foreach ($stats as $group)
        <div class="mb-10">
            <h3 class="text-lg font-bold mb-2">
                {{ $group['module']->name }} ({{ $group['module']->start_date }} - {{ $group['module']->end_date }})
            </h3>

            @if (count($group['traineeStats']) === 0)
                <p class="text-gray-500 italic">No absences recorded for this module.</p>
            @else
                <table class="w-full text-sm border bg-white rounded shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Trainee</th>
                            <th class="p-2 border">Total Absences</th>
                            <th class="p-2 border">Excused</th>
                            <th class="p-2 border">Unexcused</th>
                            <th class="p-2 border">Excused %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group['traineeStats'] as $row)
                            <tr>
                                <td class="border p-2">{{ $row['user']->name }}</td>
                                <td class="border p-2">{{ $row['total'] }}</td>
                                <td class="border p-2">{{ $row['excused'] }}</td>
                                <td class="border p-2">{{ $row['unexcused'] }}</td>
                                <td class="border p-2">{{ $row['percentage'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const moduleCtx = document.getElementById('moduleChart').getContext('2d');
    const monthCtx = document.getElementById('monthChart').getContext('2d');

    const moduleChart = new Chart(moduleCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($absencesByModule->pluck('module.name')) !!},
            datasets: [{
                label: 'Absences',
                data: {!! json_encode($absencesByModule->pluck('total')) !!},
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
