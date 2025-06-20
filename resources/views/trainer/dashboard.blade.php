@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Trainer Dashboard</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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

        <div class="flex min-h-screen container mx-auto p-6 gap-6">

            <!-- Sidebar -->
            <nav class="flex flex-col gap-4 w-60 bg-white rounded-2xl shadow p-6 sticky top-6 h-fit">

                <div class="mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Quick Actions
                </div>

                <a href="{{ route('calendar.index') }}"
                    class="block px-4 py-2 rounded bg-blue-500 text-white text-sm font-medium hover:bg-blue-600">
                    View Course Calendar
                </a>
                <a href="{{ route('trainer.reports') }}"
                    class="block px-4 py-2 rounded bg-indigo-500 text-white text-sm font-medium hover:bg-indigo-600">
                    View Reports
                </a>
                <a href="{{ route('trainer.statistics') }}"
                    class="block px-4 py-2 rounded bg-purple-500 text-white text-sm font-medium hover:bg-purple-600">
                    View Statistics
                </a>

                <div class="mt-4 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Export Options
                </div>
                <a href="{{ route('trainer.export.absences') }}"
                    class="block px-4 py-2 rounded bg-green-500 text-white text-sm font-medium hover:bg-green-600">
                    Export Absence Stats
                </a>
                <a href="{{ route('trainer.absence.email.summary') }}"
                    class="block px-4 py-2 rounded bg-yellow-500 text-white text-sm font-medium hover:bg-yellow-600">
                    Export Email Summary
                </a>

            </nav>

            <!-- Main Content -->
            <main class="flex-1">

                <div class="bg-white rounded-2xl shadow p-6">

                    <div class="flex items-center gap-3 mb-8">
                        <span
                            class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4zm-2 8c0-2.67 5.33-4 8-4s8 1.33 8 4v2H2v-2c0-2.67 5.33-4 8-4z" />
                            </svg>
                        </span>
                        <div>
                            <h1 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainer
                                Dashboard</h1>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                Welcome back! Here’s an overview of your course and trainee stats.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                            <div class="text-4xl font-bold text-blue-600">{{ $totalAbsences ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Total Absences</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                            <div class="text-4xl font-bold text-green-600">{{ $totalTrainees ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Trainees</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                            <div class="text-4xl font-bold text-red-500">{{ $totalModules ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Modules</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center transition-card">
                            <div class="text-4xl font-bold text-indigo-600">{{ $totalAttendance ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Attendance Logs</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow p-6 transition-card">
                        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h5v-2H5V5h10v10h-4v2h5a1 1 0 001-1V4a1 1 0 00-1-1H4z" />
                            </svg>
                            Recent Absences
                        </h2>
                        <ul>
                            @forelse ($recentAbsences ?? [] as $absence)
                                <li class="border-b py-2 flex justify-between transition-card">
                                    <span>{{ $absence->trainee->name ?? 'Unknown' }}</span>
                                    <span class="text-gray-500">{{ $absence->date ?? '' }}</span>
                                </li>
                            @empty
                                <li class="py-2 text-gray-400">No recent absences found.</li>
                            @endforelse
                        </ul>
                    </div>

                    <hr class="my-10 border-t border-gray-300 dark:border-gray-700" />


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

                </div>

            </main>

        </div>

    </body>
@endsection



@section('scripts')
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const absencesPerModule = {!! json_encode($absencesPerModule ?? []) !!};
            const absencesOverTime = {!! json_encode($absencesOverTime ?? []) !!};
            const absencesByReason = {!! json_encode($absencesByReason ?? []) !!};
            const topTrainees = {!! json_encode($topTrainees ?? []) !!};
            const weeklyAbsenceCounts = {!! json_encode($weeklyAbsenceCounts ?? []) !!};
            const justifiedCount = {{ $justifiedCount ?? 0 }};
            const unjustifiedCount = {{ $unjustifiedCount ?? 0 }};

            // Chart 1 – Absences Per Module
            new Chart(document.getElementById('moduleAbsenceChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(absencesPerModule),
                    datasets: [{
                        label: 'Absences',
                        data: Object.values(absencesPerModule),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Chart 2 – Absence Distribution (Doughnut)
            new Chart(document.getElementById('absenceDistributionChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(absencesPerModule),
                    datasets: [{
                        data: Object.values(absencesPerModule),
                        backgroundColor: ['#60A5FA', '#F87171', '#34D399', '#FBBF24', '#A78BFA',
                            '#F472B6', '#4ADE80'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 3 – Absences Over Time (Line)
            new Chart(document.getElementById('absencesOverTimeChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(absencesOverTime),
                    datasets: [{
                        label: 'Absences',
                        data: Object.values(absencesOverTime),
                        fill: true,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 4 – Absences by Reason
            new Chart(document.getElementById('absenceReasonChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(absencesByReason),
                    datasets: [{
                        label: 'Absences by Reason',
                        data: Object.values(absencesByReason),
                        backgroundColor: ['#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA',
                            '#FB923C'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 5 – Top Trainees (Horizontal Bar)
            new Chart(document.getElementById('topTraineesChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(topTrainees),
                    datasets: [{
                        label: 'Number of Absences',
                        data: Object.values(topTrainees),
                        backgroundColor: '#F87171',
                        borderRadius: 6,
                        barThickness: 30
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Chart 6 – Weekly Absences
            new Chart(document.getElementById('weeklyAbsencesChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(weeklyAbsenceCounts),
                    datasets: [{
                        label: 'Absences',
                        data: Object.values(weeklyAbsenceCounts),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 7 – Justified vs Unjustified
            new Chart(document.getElementById('justifiedChart'), {
                type: 'bar',
                data: {
                    labels: ['Justified', 'Unjustified'],
                    datasets: [{
                        label: 'Absences',
                        data: [justifiedCount, unjustifiedCount],
                        backgroundColor: ['#34D399', '#F87171'],
                        barThickness: 40,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
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
        });
    </script>
@endsection
