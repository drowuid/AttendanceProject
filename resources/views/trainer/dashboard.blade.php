<!DOCTYPE html>
<html>
<head>
    <title>Trainer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Trainer Dashboard</h1>

        <!-- Top Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center">
                <div class="text-gray-500 text-sm mb-2">Modules</div>
                <div class="text-3xl font-bold text-blue-600">{{ $totalModules }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center">
                <div class="text-gray-500 text-sm mb-2">Absences</div>
                <div class="text-3xl font-bold text-red-500">{{ $totalTrainerAbsences }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center">
                <div class="text-gray-500 text-sm mb-2">Trainees</div>
                <div class="text-3xl font-bold text-green-600">{{ $uniqueTrainees }}</div>
            </div>

        </div>

        <!-- Quick Links -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('calendar.index') }}" class="text-blue-600 underline text-sm">View Course Calendar</a>
            <a href="{{ route('trainer.reports') }}" class="text-blue-600 underline text-sm">View Reports</a>
            <a href="{{ route('trainer.export.absences') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-medium">
   Export Absence Stats
</a>

        </div>

        <!-- My Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-blue-50 p-6 rounded-xl shadow flex flex-col items-center">
                <h2 class="text-lg font-semibold mb-2">My Trainees</h2>
                <p class="text-3xl font-bold text-blue-700">{{ $traineesCount }}</p>
            </div>
            <div class="bg-green-50 p-6 rounded-xl shadow flex flex-col items-center">
                <h2 class="text-lg font-semibold mb-2">My Modules</h2>
                <p class="text-3xl font-bold text-green-700">{{ $modulesCount }}</p>
            </div>
            <div class="bg-red-50 p-6 rounded-xl shadow flex flex-col items-center">
                <h2 class="text-lg font-semibold mb-2">Total Absences</h2>
                <p class="text-3xl font-bold text-red-700">{{ $absencesCount }}</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-xl font-semibold mb-4">Absences Per Module</h2>
                <canvas id="moduleAbsenceChart" height="100"></canvas>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-xl font-semibold mb-4">Absence Distribution by Module</h2>
                <canvas id="absenceDistributionChart" height="100"></canvas>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-xl font-semibold mb-4">Absences Over Time</h2>
                <canvas id="absencesOverTimeChart" height="100"></canvas>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-xl font-semibold mb-4">Absences by Reason</h2>
                <canvas id="absenceReasonChart" width="400" height="400"></canvas>
            </div>
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Weekly Absences</h2>
    <canvas id="weeklyAbsencesChart" height="100"></canvas>

    <div class="bg-white p-4 rounded-xl shadow-md">
    <div class="text-gray-500 text-sm">Absences This Week</div>
    <div class="text-2xl font-semibold text-red-600">{{ $weeklyAbsencesCount }}</div>
</div>

    <div class="bg-white p-4 rounded-xl shadow-md">
    <div class="text-gray-500 text-sm">Justification Rate</div>
    <div class="text-2xl font-semibold text-indigo-600">{{ $justificationRate }}%</div>
</div>



</div>
            <div class="bg-white p-6 rounded-2xl shadow lg:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Top 5 Trainees with Most Absences</h2>
                <canvas id="topTraineesChart" height="150"></canvas>
            </div>
        </div>

        <!-- Justification Rate -->
<div class="bg-white rounded-2xl shadow p-6 w-full sm:w-1/2 lg:w-1/3">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Justification Rate</h2>
    <div class="relative w-32 h-32 mx-auto">
        <svg class="absolute top-0 left-0 w-full h-full transform -rotate-90" viewBox="0 0 36 36">
            <path
                class="text-gray-300"
                stroke="currentColor"
                stroke-width="4"
                fill="none"
                d="M18 2a16 16 0 1 1 0 32 16 16 0 0 1 0-32"
            />
            <path
                class="text-green-500"
                stroke="currentColor"
                stroke-width="4"
                stroke-dasharray="{{ $justificationRate }}, 100"
                fill="none"
                d="M18 2a16 16 0 1 1 0 32 16 16 0 0 1 0-32"
            />
        </svg>
        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
            <span class="text-xl font-bold text-gray-700">{{ $justificationRate }}%</span>
        </div>
    </div>
</div>

<!-- Justified vs Unjustified Absences Bar Chart -->
<div class="bg-white rounded-2xl shadow p-6 w-full sm:w-1/2 lg:w-1/3">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Justified vs Unjustified Absences</h2>
    <canvas id="justifiedChart" height="200"></canvas>
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

        <!-- Latest Absences Table -->
        <div class="bg-white rounded-2xl shadow p-6 mb-10">
            <h2 class="text-xl font-semibold mb-4">Latest Absences</h2>
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">Trainee</th>
                        <th class="px-4 py-3">Module</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestAbsences as $absence)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3">{{ $absence->attendance->trainee->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $absence->module->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $absence->reason ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center">No recent absences</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

        <!-- Module Attendance Tables -->
        @if($modules->count() === 0)
            <p class="text-gray-500">No modules found. Please add a module first.</p>
        @else
            @foreach($modules as $module)
                <div class="mb-8 bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $module->name }} ({{ $module->start_date }} - {{ $module->end_date }})</h2>
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
        // Absences Per Module Bar Chart
        new Chart(document.getElementById('moduleAbsenceChart').getContext('2d'), {
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
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Absence Distribution by Module Doughnut Chart
        new Chart(document.getElementById('absenceDistributionChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($absencesPerModule->keys()) !!},
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
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Absences Over Time Line Chart
        new Chart(document.getElementById('absencesOverTimeChart').getContext('2d'), {
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
                    x: { title: { display: true, text: 'Month' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Absences' } }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });

        // Absences by Reason Doughnut Chart
        new Chart(document.getElementById('absenceReasonChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($absencesByReason->keys()) !!},
                datasets: [{
                    label: 'Absences by Reason',
                    data: {!! json_encode($absencesByReason->values()) !!},
                    backgroundColor: [
                        '#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#FB923C'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });

        // Top Trainees with Most Absences Horizontal Bar Chart
        new Chart(document.getElementById('topTraineesChart').getContext('2d'), {
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
                        title: { display: true, text: 'Absences' }
                    },
                    y: { title: { display: false } }
                }
            }
        });

// Weekly Absences Line Chart
const weeklyAbsencesCtx = document.getElementById('weeklyAbsencesChart').getContext('2d');
new Chart(weeklyAbsencesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($weeklyAbsenceCounts->keys()) !!},
        datasets: [{
            label: 'Absences',
            data: {!! json_encode($weeklyAbsenceCounts->values()) !!},
            fill: true,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            tension: 0.4,
            pointBackgroundColor: '#3B82F6'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: getComputedStyle(document.documentElement).classList.contains('dark') ? '#fff' : '#000'
                }
            },
            x: {
                ticks: {
                    color: getComputedStyle(document.documentElement).classList.contains('dark') ? '#fff' : '#000'
                }
            }
        }
    }
});

// Justified vs Unjustified Absences Bar Chart
const justifiedCtx = document.getElementById('justifiedChart').getContext('2d');
    new Chart(justifiedCtx, {
        type: 'bar',
        data: {
            labels: ['Justified', 'Unjustified'],
            datasets: [{
                label: 'Absences',
                data: [{{ $justifiedCount }}, {{ $unjustifiedCount }}],
                backgroundColor: ['#34D399', '#F87171'],
                borderRadius: 6,
                barThickness: 40,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
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
</body>
</html>
