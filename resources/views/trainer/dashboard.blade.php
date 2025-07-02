@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="container mx-auto flex flex-col lg:flex-row gap-6">

            <!-- Main Content -->
            <main class="flex-1 flex flex-col gap-5">
                <!-- Header -->
                <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4zM10 19c0-2.67 5.33-4 8-4s8 1.33 8 4v2H2v-2c0-2.67 5.33-4 8-4z" />
                        </svg>
                    </span>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainer Dashboard
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                            Overview of your trainees and course activity.
                        </p>
                    </div>

                </div>
                <!-- Quick Actions & Export Options -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    <a href="{{ route('trainer.calendar.index') }}"
                        class="flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition-all text-xs font-medium text-center">
                        📅 View Course Calendar
                    </a>
                    <a href="{{ route('trainer.reports') }}"
                        class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded shadow hover:bg-indigo-600 transition-all text-xs font-medium text-center">
                        📊 View Reports
                    </a>
                    <a href="{{ route('trainer.statistics') }}"
                        class="flex items-center justify-center px-4 py-2 bg-purple-500 text-white rounded shadow hover:bg-purple-600 transition-all text-xs font-medium text-center">
                        📈 View Statistics
                    </a>
                </div>


                <!-- Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Total Absences</h2>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalAbsences ?? 0 }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Trainees</h2>
                        <p class="text-2xl font-bold text-green-600">{{ $totalTrainees ?? 0 }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Modules</h2>
                        <p class="text-2xl font-bold text-red-500">{{ $totalModules ?? 0 }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Attendance Logs</h2>
                        <p class="text-2xl font-bold text-indigo-600">{{ $totalAttendance ?? 0 }}</p>
                    </div>
                </div>

                <div class="flex justify-center mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <a href="{{ route('trainer.export.absences') }}"
                            class="flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transition-all text-xs font-medium text-center">
                            📝 Export Absence Stats
                        </a>
                        <a href="{{ route('trainer.absence.email.summary') }}"
                            class="flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600 transition-all text-xs font-medium text-center">
                            ✉️ Export Email Summary
                        </a>
                    </div>
                </div>

                <!-- Recent Absences Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-white">Recent Trainee Absences</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-left">
                            <thead>
                                <tr
                                    class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-1">Trainee</th>
                                    <th class="py-1">Module</th>
                                    <th class="py-1">Date</th>
                                    <th class="py-1">Justified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAbsences as $absence)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-1">
                                            @if ($absence->trainee && isset($absence->trainee->id) && isset($absence->trainee->name))
                                                <a href="{{ route('trainer.trainee.profile', $absence->trainee->id) }}"
                                                    class="text-blue-600 hover:underline">
                                                    {{ $absence->trainee->name }}
                                                </a>
                                            @else
                                                Unknown
                                            @endif
                                        </td>
                                        <td class="py-1">{{ $absence->module->name ?? 'Unknown' }}</td>
                                        <td class="py-1">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-1">
                                            @if ($absence->justified)
                                                <span class="text-green-600 font-medium">Yes</span>
                                            @else
                                                <span class="text-red-600 font-medium">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-2 text-gray-500 dark:text-gray-400">
                                            No absences recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <h2 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">Top Absent Trainees</h2>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        @forelse ($topAbsentTrainees as $trainee)
                            <li class="flex justify-between items-center">
                                <a href="{{ route('trainer.trainee.profile', $trainee['id'] ?? '') }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $trainee['name'] ?? 'Unknown' }}
                                </a>
                                <span class="text-xs text-gray-500">
                                    {{ $trainee['absences_count'] ?? 0 }} absences
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-400">No data available.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Top Justified Trainees -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mt-6">
                    <h2 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">Top 5 Justified Trainees</h2>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        @forelse ($topJustifiedTrainees as $trainee)
                            <li class="flex justify-between items-center">
                                <a href="{{ route('trainer.trainee.profile', $trainee['id']) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $trainee['name'] }}
                                </a>
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">
                                    {{ $trainee['count'] }} justified
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-400">No data available.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Recent Justified Absences -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mt-6">
                    <h2 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">Recent Justified Absences</h2>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        @forelse ($recentJustifiedAbsences as $absence)
                            <li class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('trainer.trainee.profile', $absence['trainee']['id']) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $absence['trainee']['name'] ?? 'Unknown' }}
                                    </a>
                                    <span class="text-gray-500">— {{ $absence['module']['name'] ?? 'Unknown Module' }}</span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($absence['date'])->format('d/m/Y') }}
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-400">No justified absences found.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <h2 class="text-base font-semibold mb-3 text-gray-900 dark:text-white">Recent Unjustified Absences</h2>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        @forelse ($recentUnjustifiedAbsences as $absence)
                            <li class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('trainer.trainee.profile', $absence['trainee']['id']) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $absence['trainee']['name'] ?? 'Unknown' }}
                                    </a>
                                    <span class="text-gray-500">— {{ $absence['module']['name'] ?? 'Unknown Module' }}</span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($absence['date'])->format('d/m/Y') }}
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-400">No unjustified absences found.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ([
            'Absences Per Module' => 'moduleAbsenceChart',
            'Absence Distribution' => 'absenceDistributionChart',
            'Absences Over Time' => 'absencesOverTimeChart',
            'Absences by Reason' => 'absenceReasonChart',
            'Top Trainees' => 'topTraineesChart',
            'Weekly Absences' => 'weeklyAbsencesChart',
            'Justified vs Unjustified' => 'justifiedChart',
        ] as $title => $id)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                            <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">{{ $title }}</h2>
                            <canvas id="{{ $id }}" width="220" height="120"></canvas>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get data from backend with fallbacks
            const absencesPerModule = @json($absencesPerModule ?? []);
            const absencesOverTime = @json($absencesOverTime ?? []);
            const absencesByReason = @json($absencesByReason ?? []);
            const topTrainees = @json($topTrainees ?? []);
            const weeklyAbsenceCounts = @json($weeklyAbsenceCounts ?? []);
            const justifiedCount = {{ $justifiedCount ?? 0 }};
            const unjustifiedCount = {{ $unjustifiedCount ?? 0 }};

            const createChart = (ctx, config) => {
                if (!ctx) return null;
                try {
                    return new Chart(ctx, config);
                } catch (error) {
                    console.error('Error creating chart:', error);
                    return null;
                }
            };

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                animation: {
                    duration: 0
                },
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            };

            const doughnutOptions = {
                ...commonOptions,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            };

            const barOptions = {
                ...commonOptions,
                aspectRatio: 1.5,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            };

            // Module Absence Chart
            if (Object.keys(absencesPerModule).length > 0) {
                const ctx1 = document.getElementById('moduleAbsenceChart');
                if (ctx1) {
                    createChart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(absencesPerModule),
                            datasets: [{
                                label: 'Absences',
                                data: Object.values(absencesPerModule),
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: barOptions
                    });
                }

                // Absence Distribution Chart
                const ctx2 = document.getElementById('absenceDistributionChart');
                if (ctx2) {
                    createChart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(absencesPerModule),
                            datasets: [{
                                data: Object.values(absencesPerModule),
                                backgroundColor: [
                                    '#60A5FA', '#F87171', '#34D399', 
                                    '#FBBF24', '#A78BFA', '#FB7185',
                                    '#4ADE80', '#FACC15', '#C084FC'
                                ]
                            }]
                        },
                        options: doughnutOptions
                    });
                }
            } else {
                // Show "No data" message for module charts
                ['moduleAbsenceChart', 'absenceDistributionChart'].forEach(chartId => {
                    const canvas = document.getElementById(chartId);
                    if (canvas) {
                        const ctx = canvas.getContext('2d');
                        ctx.font = '14px Arial';
                        ctx.fillStyle = '#9CA3AF';
                        ctx.textAlign = 'center';
                        ctx.fillText('No data available', canvas.width / 2, canvas.height / 2);
                    }
                });
            }

            // Absences Over Time Chart
            if (Object.keys(absencesOverTime).length > 0) {
                const ctx3 = document.getElementById('absencesOverTimeChart');
                if (ctx3) {
                    createChart(ctx3, {
                        type: 'line',
                        data: {
                            labels: Object.keys(absencesOverTime),
                            datasets: [{
                                label: 'Absences Over Time',
                                data: Object.values(absencesOverTime),
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: barOptions
                    });
                }
            }

            // Absence Reason Chart
            if (Object.keys(absencesByReason).length > 0) {
                const ctx4 = document.getElementById('absenceReasonChart');
                if (ctx4) {
                    createChart(ctx4, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(absencesByReason),
                            datasets: [{
                                data: Object.values(absencesByReason),
                                backgroundColor: ['#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA']
                            }]
                        },
                        options: doughnutOptions
                    });
                }
            }

            // Top Trainees Chart
            if (Object.keys(topTrainees).length > 0) {
                const ctx5 = document.getElementById('topTraineesChart');
                if (ctx5) {
                    createChart(ctx5, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(topTrainees),
                            datasets: [{
                                label: 'Top Trainees',
                                data: Object.values(topTrainees),
                                backgroundColor: '#F87171'
                            }]
                        },
                        options: {
                            ...barOptions,
                            indexAxis: 'y',
                            aspectRatio: 1.2
                        }
                    });
                }
            }

            // Weekly Absences Chart
            if (Object.keys(weeklyAbsenceCounts).length > 0) {
                const ctx6 = document.getElementById('weeklyAbsencesChart');
                if (ctx6) {
                    createChart(ctx6, {
                        type: 'line',
                        data: {
                            labels: Object.keys(weeklyAbsenceCounts),
                            datasets: [{
                                label: 'Weekly Absences',
                                data: Object.values(weeklyAbsenceCounts),
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: barOptions
                    });
                }
            }

            // Justified vs Unjustified Chart
            const ctx7 = document.getElementById('justifiedChart');
            if (ctx7) {
                createChart(ctx7, {
                    type: 'bar',
                    data: {
                        labels: ['Justified', 'Unjustified'],
                        datasets: [{
                            label: 'Absences',
                            data: [justifiedCount, unjustifiedCount],
                            backgroundColor: ['#34D399', '#F87171']
                        }]
                    },
                    options: {
                        ...barOptions,
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
            }

            // Handle empty charts
            ['absenceReasonChart', 'topTraineesChart', 'weeklyAbsencesChart', 'absencesOverTimeChart'].forEach(chartId => {
                const canvas = document.getElementById(chartId);
                if (canvas && !canvas.chart) {
                    const ctx = canvas.getContext('2d');
                    ctx.font = '14px Arial';
                    ctx.fillStyle = '#9CA3AF';
                    ctx.textAlign = 'center';
                    ctx.fillText('No data available', canvas.width / 2, canvas.height / 2);
                }
            });
        });
    </script>
@endsection