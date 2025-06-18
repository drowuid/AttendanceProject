<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .transition-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .transition-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">
    <div class="container mx-auto p-6">
        <div class="flex items-center gap-3 mb-8">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4zm-2 8c0-2.67 5.33-4 8-4s8 1.33 8 4v2H2v-2c0-2.67 5.33-4 8-4z" />
            </svg>
            </span>
            <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainer Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 text-base mt-1">Welcome back! Here’s an overview of your course and trainee stats.</p>
            </div>
        </div>

        <!-- Quick Links Sidebar -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('calendar.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm font-medium">
                View Course Calendar
            </a>
            <a href="{{ route('trainer.reports') }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-sm font-medium">
                View Reports
            </a>
            <a href="{{ route('trainer.export.absences') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-medium">
                Export Absence Stats
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                <div class="text-4xl font-bold text-blue-600">{{ $totalAbsences }}</div>
                <div class="text-sm text-gray-500">Total Absences</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                <div class="text-4xl font-bold text-green-600">{{ $totalTrainees }}</div>
                <div class="text-sm text-gray-500">Trainees</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                <div class="text-4xl font-bold text-red-500">{{ $totalModules }}</div>
                <div class="text-sm text-gray-500">Modules</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                <div class="text-4xl font-bold text-indigo-600">{{ $totalAttendance }}</div>
                <div class="text-sm text-gray-500">Attendance Logs</div>
            </div>
        </div>

        <hr class="my-10 border-t border-gray-300 dark:border-gray-700">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Absences Per Module</h2>
                <canvas id="moduleAbsenceChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Absence Distribution</h2>
                <canvas id="absenceDistributionChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Absences Over Time</h2>
                <canvas id="absencesOverTimeChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Absences by Reason</h2>
                <canvas id="absenceReasonChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Top Trainees</h2>
                <canvas id="topTraineesChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Weekly Absences</h2>
                <canvas id="weeklyAbsencesChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 transition-card">
                <h2 class="text-xl font-semibold mb-4">Justified vs Unjustified</h2>
                <canvas id="justifiedChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 transition-card">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h5v-2H5V5h10v10h-4v2h5a1 1 0 001-1V4a1 1 0 00-1-1H4z"/>
                </svg>
                Recent Absences
            </h2>
            <ul>
                @foreach ($recentAbsences as $absence)
                    <li class="border-b py-2 flex justify-between transition-card">
                        <span>{{ $absence->trainee->name }}</span>
                        <span class="text-gray-500">{{ $absence->date }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
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
