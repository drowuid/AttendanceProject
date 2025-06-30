@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto flex flex-col lg:flex-row gap-6">
        <!-- Sidebar -->
        <aside class="w-full lg:w-64 bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col gap-5 text-xs sticky top-8 h-fit self-start">
            <div>
                <div class="mb-2 font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    Quick Actions
                </div>
                <a href="{{ route('calendar.index') }}"
                   class="block px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs font-medium text-center transition-all duration-150">
                    View Course Calendar
                </a>
                <a href="{{ route('trainer.reports') }}"
                   class="block px-3 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs font-medium text-center transition-all duration-150 mt-2">
                    View Reports
                </a>
                <a href="{{ route('trainer.statistics') }}"
                   class="block px-3 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 text-xs font-medium text-center transition-all duration-150 mt-2">
                    View Statistics
                </a>
            </div>
            <div>
                <div class="mb-2 font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    Export Options
                </div>
                <a href="{{ route('trainer.export.absences') }}"
                   class="block px-3 py-2 text-xs font-medium text-white bg-green-500 hover:bg-green-600 rounded text-center transition-all duration-150 mb-2">
                    Export Absence Stats
                </a>
                <a href="{{ route('trainer.absence.email.summary') }}"
                   class="block px-3 py-2 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded text-center transition-all duration-150">
                    Export Email Summary
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col gap-5">
            <!-- Header -->
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-300"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4zM10 19c0-2.67 5.33-4 8-4s8 1.33 8 4v2H2v-2c0-2.67 5.33-4 8-4z"/>
                    </svg>
                </span>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainer Dashboard</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                        Overview of your trainees and course activity.
                    </p>
                </div>
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

            <!-- Recent Absences Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-white">Recent Trainee Absences</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                        <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                            <th class="py-1">Trainee</th>
                            <th class="py-1">Module</th>
                            <th class="py-1">Date</th>
                            <th class="py-1">Justified</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($recentAbsences as $absence)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-1">{{ $absence->trainee->name ?? 'Unknown' }}</td>
                                <td class="py-1">{{ $absence->module->name ?? 'Unknown' }}</td>
                                <td class="py-1">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
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

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ([
                    'Absences Per Module' => 'moduleAbsenceChart',
                    'Absence Distribution' => 'absenceDistributionChart',
                    'Absences Over Time' => 'absencesOverTimeChart',
                    'Absences by Reason' => 'absenceReasonChart',
                    'Top Trainees' => 'topTraineesChart',
                    'Weekly Absences' => 'weeklyAbsencesChart',
                    'Justified vs Unjustified' => 'justifiedChart'
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
        // Global chart instances tracker
        window.chartInstances = window.chartInstances || {};

        // Prevent multiple initializations
        if (typeof window.chartsInitialized === 'undefined') {
            window.chartsInitialized = false;
        }

        function destroyExistingCharts() {
            // Destroy all existing chart instances
            Object.keys(window.chartInstances).forEach(chartId => {
                if (window.chartInstances[chartId]) {
                    window.chartInstances[chartId].destroy();
                    delete window.chartInstances[chartId];
                }
            });
        }

        function initializeCharts() {
            // Exit if charts are already initialized
            if (window.chartsInitialized) {
                return;
            }

            // Destroy any existing charts first
            destroyExistingCharts();

            const absencesPerModule = {!! json_encode($absencesPerModule ?? []) !!};
            const absencesOverTime = {!! json_encode($absencesOverTime ?? []) !!};
            const absencesByReason = {!! json_encode($absencesByReason ?? []) !!};
            const topTrainees = {!! json_encode($topTrainees ?? []) !!};
            const weeklyAbsenceCounts = {!! json_encode($weeklyAbsenceCounts ?? []) !!};
            const justifiedCount = {{ $justifiedCount ?? 0 }};
            const unjustifiedCount = {{ $unjustifiedCount ?? 0 }};

            // Common options with proper responsive settings
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2, // Fixed aspect ratio
                animation: {
                    duration: 0 // Disable animations to prevent loops
                },
                layout: {
                    padding: 10
                }
            };

            // Specific options for different chart types
            const doughnutOptions = {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1, // Square aspect ratio for doughnut charts
                animation: {
                    duration: 0
                },
                layout: {
                    padding: 10
                }
            };

            const verticalBarOptions = {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                animation: {
                    duration: 0
                },
                layout: {
                    padding: 10
                }
            };

            // Chart 1 – Absences Per Module
            const moduleAbsenceElement = document.getElementById('moduleAbsenceChart');
            if (moduleAbsenceElement) {
                window.chartInstances.moduleAbsenceChart = new Chart(moduleAbsenceElement, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(absencesPerModule),
                        datasets: [{
                            label: 'Absences',
                            data: Object.values(absencesPerModule),
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Chart 2 – Absence Distribution (Doughnut)
            const absenceDistributionElement = document.getElementById('absenceDistributionChart');
            if (absenceDistributionElement) {
                window.chartInstances.absenceDistributionChart = new Chart(absenceDistributionElement, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(absencesPerModule),
                        datasets: [{
                            data: Object.values(absencesPerModule),
                            backgroundColor: ['#60A5FA', '#F87171', '#34D399', '#FBBF24', '#A78BFA', '#F472B6', '#4ADE80']
                        }]
                    },
                    options: doughnutOptions
                });
            }

            // Chart 3 – Absences Over Time (Line)
            const absencesOverTimeElement = document.getElementById('absencesOverTimeChart');
            if (absencesOverTimeElement) {
                window.chartInstances.absencesOverTimeChart = new Chart(absencesOverTimeElement, {
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
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Chart 4 – Absences by Reason (Doughnut)
            const absenceReasonElement = document.getElementById('absenceReasonChart');
            if (absenceReasonElement) {
                window.chartInstances.absenceReasonChart = new Chart(absenceReasonElement, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(absencesByReason),
                        datasets: [{
                            label: 'Absences by Reason',
                            data: Object.values(absencesByReason),
                            backgroundColor: ['#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#FB923C']
                        }]
                    },
                    options: doughnutOptions
                });
            }

            // Chart 5 – Top Trainees (Horizontal Bar)
            const topTraineesElement = document.getElementById('topTraineesChart');
            if (topTraineesElement) {
                window.chartInstances.topTraineesChart = new Chart(topTraineesElement, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(topTrainees),
                        datasets: [{
                            label: 'Number of Absences',
                            data: Object.values(topTrainees),
                            backgroundColor: '#F87171',
                            borderRadius: 6,
                            barThickness: 20
                        }]
                    },
                    options: {
                        ...commonOptions,
                        indexAxis: 'y',
                        aspectRatio: 1.2, // Adjusted for horizontal bars
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Chart 6 – Weekly Absences (Line)
            const weeklyAbsencesElement = document.getElementById('weeklyAbsencesChart');
            if (weeklyAbsencesElement) {
                window.chartInstances.weeklyAbsencesChart = new Chart(weeklyAbsencesElement, {
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
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Chart 7 – Justified vs Unjustified (Bar)
            const justifiedElement = document.getElementById('justifiedChart');
            if (justifiedElement) {
                window.chartInstances.justifiedChart = new Chart(justifiedElement, {
                    type: 'bar',
                    data: {
                        labels: ['Justified', 'Unjustified'],
                        datasets: [{
                            label: 'Absences',
                            data: [justifiedCount, unjustifiedCount],
                            backgroundColor: ['#34D399', '#F87171'],
                            borderRadius: 6,
                            barThickness: 40
                        }]
                    },
                    options: {
                        ...verticalBarOptions,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            }
                        }
                    }
                });
            }

            // Mark charts as initialized
            window.chartsInitialized = true;
        }

        // Clean up function for page unload
        function cleanupCharts() {
            destroyExistingCharts();
            window.chartsInitialized = false;
        }

        // Initialize charts when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeCharts);
        } else {
            // DOM is already ready
            initializeCharts();
        }

        // Clean up on page unload
        window.addEventListener('beforeunload', cleanupCharts);

        // Handle visibility change (when user switches tabs)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Page is hidden, pause any animations
                Object.values(window.chartInstances).forEach(chart => {
                    if (chart && chart.options && chart.options.animation) {
                        chart.options.animation.duration = 0;
                    }
                });
            }
        });
    </script>
@endsection
