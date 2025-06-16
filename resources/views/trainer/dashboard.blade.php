<!DOCTYPE html>
<html>
<head>
    <title>Trainer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Trainer Dashboard</h1>

        <!-- Links -->
        <a href="{{ route('calendar.index') }}" class="text-blue-600 underline text-sm mb-4 inline-block">View Course Calendar</a>
        <a href="{{ route('trainer.reports') }}" class="text-blue-600 underline ml-4 text-sm">View Reports</a>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 mt-6">
            <div class="bg-gray-100 p-4 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-2">My Trainees</h2>
                <p class="text-3xl font-bold">{{ $traineesCount }}</p>
            </div>

            <div class="bg-gray-100 p-4 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-2">My Modules</h2>
                <p class="text-3xl font-bold">{{ $modulesCount }}</p>
            </div>

            <div class="bg-gray-100 p-4 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-2">Total Absences</h2>
                <p class="text-3xl font-bold">{{ $absencesCount }}</p>
            </div>
        </div>

        <!-- Absence Chart -->
        <div class="mt-10 bg-white p-6 rounded-2xl shadow mb-10">
            <h2 class="text-xl font-semibold mb-4">Absences Per Module</h2>
            <canvas id="moduleAbsenceChart" height="100"></canvas>
        </div>

        <!-- Absence Distribution Chart -->
<div class="mt-10 bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">Absence Distribution by Module</h2>
    <canvas id="absenceDistributionChart" height="100"></canvas>
</div>

<!-- Absences Over Time Chart -->
<div class="mt-10 bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">Absences Over Time</h2>
    <canvas id="absencesOverTimeChart" height="100"></canvas>
</div>

<!-- Top Trainees with Most Absences Chart -->
<div class="mt-10 bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">Top 5 Trainees with Most Absences</h2>
    <canvas id="topTraineesChart" height="150"></canvas>
</div>


        <!-- Upcoming Modules -->
        <div class="bg-white p-6 rounded-xl shadow mb-10">
            <h2 class="text-xl font-semibold mb-4">Upcoming Modules</h2>

            @if($upcomingModules->isEmpty())
                <p class="text-sm text-gray-500 italic">You have no upcoming modules scheduled.</p>
            @else
                <ul class="space-y-2">
                    @foreach($upcomingModules as $upcoming)
                        <li class="p-3 bg-gray-100 rounded-xl shadow-sm flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-lg">{{ $upcoming->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($upcoming->start_date)->format('M d, Y') }}
                                    →
                                    {{ \Carbon\Carbon::parse($upcoming->end_date)->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                Starts in {{ \Carbon\Carbon::parse($upcoming->start_date)->diffInDays(now()) }} days
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Trainee Absence Summary -->
        <div class="bg-white p-6 rounded-xl shadow mb-10">
            <h2 class="text-xl font-semibold mb-4">Trainee Absence Summary</h2>

            @if($traineeAbsenceSummary->isEmpty())
                <p class="text-sm text-gray-500 italic">No absences recorded yet.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($traineeAbsenceSummary as $summary)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-lg">
                                    {{ $summary['trainee']?->name ?? 'Unknown Trainee' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Email: {{ $summary['trainee']?->email ?? '-' }}
                                </p>
                            </div>
                            <span class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                {{ $summary['absences'] }} Absences
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Absences Timeline -->
        <div class="bg-white p-6 rounded-xl shadow mb-10">
            <h2 class="text-xl font-semibold mb-4">Recent Absences</h2>

            @if($recentAbsences->isEmpty())
                <p class="text-sm text-gray-500 italic">No recent absences recorded.</p>
            @else
                <ul class="space-y-4">
                    @foreach($recentAbsences as $absence)
                        <li class="flex justify-between items-start border-l-4 border-red-400 pl-4">
                            <div>
                                <p class="font-semibold">{{ $absence->trainee?->name ?? 'Unknown Trainee' }}</p>
                                <p class="text-sm text-gray-600">
                                    Module: {{ $absence->module?->name ?? 'Unknown' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($absence->date)->format('d M Y') }}
                                </p>
                            </div>
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Absent</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Module Attendance Table -->
        @if($modules->count() === 0)
            <p class="text-gray-500">No modules found. Please add a module first.</p>
        @else
            @foreach($modules as $module)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold">{{ $module->name }} ({{ $module->start_date }} - {{ $module->end_date }})</h2>

                    @if($module->attendances->count() === 0)
                        <p class="text-sm text-gray-500 italic">No attendance records for this module yet.</p>
                    @else
                        <table class="min-w-full text-sm mt-2 border rounded">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="p-2 text-left">Trainee</th>
                                    <th class="p-2 text-left">Days Present</th>
                                    <th class="p-2 text-left">Days Absent</th>
                                    <th class="p-2 text-left">Late Arrivals</th>
                                    <th class="p-2 text-left">Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $moduleDays = \Carbon\Carbon::parse($module->start_date)->diffInDays(\Carbon\Carbon::parse($module->end_date)) + 1;
                                @endphp

                                @foreach($module->attendances->groupBy('trainee_id') as $traineeId => $records)
                                    @php
                                        $trainee = $records->first()->trainee ?? null;
                                        $present = $records->where('status', 'present')->count();
                                        $late = $records->where('status', 'late')->count();
                                        $absent = $moduleDays - $records->count();
                                        $hours = $records->reduce(function ($carry, $attendance) {
                                            if ($attendance->entry_time && $attendance->exit_time) {
                                                $entry = \Carbon\Carbon::parse($attendance->entry_time);
                                                $exit = \Carbon\Carbon::parse($attendance->exit_time);
                                                return $carry + $exit->diffInMinutes($entry) / 60;
                                            }
                                            return $carry;
                                        }, 0);
                                    @endphp

                                    @if($trainee)
                                        <tr class="border-t">
                                            <td class="p-2">{{ $trainee->name }}</td>
                                            <td class="p-2">{{ $present }}</td>
                                            <td class="p-2">{{ $absent }}</td>
                                            <td class="p-2">{{ $late }}</td>
                                            <td class="p-2">{{ number_format($hours, 2) }}h</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const moduleAbsenceCtx = document.getElementById('moduleAbsenceChart').getContext('2d');
        new Chart(moduleAbsenceCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($absencesPerModule->keys()) !!},
                datasets: [{
const moduleAbsenceCtx = document.getElementById('moduleAbsenceChart').getContext('2d');
new Chart(moduleAbsenceCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($absencesPerModule->keys()) !!},
        datasets: [{
            label: 'Absences',
            data: {!! json_encode($absencesPerModule->values()) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
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
        datasets: [{
            data: {!! json_encode($absencesPerModule->values()) !!},
            backgroundColor: [
                '#60A5FA', '#F87171', '#34D399', '#FBBF24', '#A78BFA', '#F472B6', '#4ADE80'
            ],
            borderWidth: 1,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw;
                        const total = context.chart._metasets[0].total;
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

const absencesOverTimeCtx = document.getElementById('absencesOverTimeChart').getContext('2d');
new Chart(absencesOverTimeCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($absencesOverTime->keys()) !!},
        datasets: [{
            label: 'Absences',
            data: {!! json_encode($absencesOverTime->values()) !!},
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3B82F6',
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Absences'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});

const topTraineesCtx = document.getElementById('topTraineesChart').getContext('2d');
new Chart(topTraineesCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($topTrainees->keys()) !!},
        datasets: [{
            label: 'Number of Absences',
            data: {!! json_encode($topTrainees->values()) !!},
            backgroundColor: '#F87171',
            borderRadius: 6,
            barThickness: 30,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw} absences`;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Absences'
                }
            },
            y: {
                title: {
                    display: false
                }
            }
        }
    }
});



    </script>
</body>
</html>
