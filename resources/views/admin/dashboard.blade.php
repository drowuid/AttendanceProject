@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="container mx-auto flex flex-col lg:flex-row gap-8">

            <!-- Sidebar -->
            <aside
                class="w-full lg:w-56 bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-6 lg:mb-0 lg:sticky top-8 h-fit flex flex-col gap-4">
                <div>
                    <div class="mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Quick Actions
                    </div>
                    <a href="{{ route('admin.absences.index') }}"
                        class="w-full block px-3 py-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-xs font-medium text-center transition-all duration-150">
                        Manage Absences
                    </a>
                </div>
                <div>
                    <div class="mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Export Options
                    </div>
                    <a href="{{ route('admin.modules.export') }}"
                        class="w-full block px-3 py-1.5 text-xs font-medium text-white bg-green-500 hover:bg-green-600 rounded-md text-center transition-all duration-150 mb-2">
                        Modules CSV
                    </a>
                    <a href="{{ route('admin.admin.export.topTrainees') }}"
                        class="w-full block px-3 py-1.5 text-xs font-medium text-white bg-purple-500 hover:bg-purple-600 rounded-md text-center transition-all duration-150 mb-2">
                        Top Trainees CSV
                    </a>
                    <a href="{{ route('admin.admin.export.absencesByReason') }}"
                        class="w-full block px-3 py-1.5 text-xs font-medium text-white bg-pink-500 hover:bg-pink-600 rounded-md text-center transition-all duration-150">
                        Absences by Reason CSV
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col gap-8">
                <!-- Header -->
                <div class="flex items-center gap-4 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600 dark:text-red-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h18v18H3V3zm3 6h12v2H6V9zm0 4h12v2H6v-2z" />
                        </svg>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Admin Dashboard
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-base mt-1">
                            Overview of platform activity and stats.
                        </p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Total Users</h2>
                        <p class="text-4xl font-bold text-blue-600">{{ $userCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Total Trainers</h2>
                        <p class="text-4xl font-bold text-purple-600">{{ $trainerCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Total Trainees</h2>
                        <p class="text-4xl font-bold text-green-600">{{ $traineesCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Absence Records</h2>
                        <p class="text-4xl font-bold text-red-600">{{ $absenceCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Marked Absences</h2>
                        <p class="text-4xl font-bold text-red-500">{{ $absencesCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Modules</h2>
                        <p class="text-4xl font-bold text-yellow-600">{{ $moduleCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Courses</h2>
                        <p class="text-4xl font-bold text-indigo-600">{{ $courseCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">Active Sessions</h2>
                        <p class="text-4xl font-bold text-gray-700 dark:text-gray-100">{{ $activeSessions ?? 0 }}</p>
                    </div>
                </div>

                <!-- User Management Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Recent Users</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr
                                    class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Email</th>
                                    <th class="py-2">Role</th>
                                    <th class="py-2">Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $user)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2">{{ $user->name }}</td>
                                        <td class="py-2">{{ $user->email }}</td>
                                        <td class="py-2 capitalize">{{ $user->role }}</td>
                                        <td class="py-2">{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No
                                            recent users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Trainee Absences -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Recent Trainee Absences</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr
                                    class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-2">Trainee</th>
                                    <th class="py-2">Module</th>
                                    <th class="py-2">Date</th>
                                    <th class="py-2">Justified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAbsentees as $absence)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2">{{ $absence->trainee->name ?? 'Unknown' }}</td>
                                        <td class="py-2">{{ $absence->module->name ?? 'Unknown' }}</td>
                                        <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-2">
                                            @if ($absence->justified)
                                                <span class="text-green-600 font-medium">Yes</span>
                                            @else
                                                <span class="text-red-600 font-medium">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No
                                            absences recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Absences Line Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Monthly Absences</h2>
                    <canvas id="monthlyAbsenceLineChart" height="100"></canvas>
                </div>

                <!-- Absences Per Module Bar Chart -->
                <div class="bg-white rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4">Absences Per Module</h2>
                    <canvas id="moduleAbsenceBarChart" height="100"></canvas>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Absences per Month</h2>
                        <div class="relative" style="height:320px;">
                            <canvas id="absenceChart"
                                style="width:100% !important; height:100% !important; display:block;"></canvas>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col">
                        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Absences per Module</h2>
                        <div class="relative" style="height:320px;">
                            <canvas id="absenceModuleChart"
                                style="width:100% !important; height:100% !important; display:block;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Justified vs Unjustified Chart -->
                <div class="bg-white rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4">Justified vs Unjustified Absences</h2>
                    <canvas id="justifiedChart" height="100"></canvas>
                </div>

                <!-- Weekly Absences -->
                <div class="bg-white rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4">Weekly Absences</h2>
                    <canvas id="weeklyAbsencesChart" height="100"></canvas>
                </div>

                <!-- Absences by Reason -->
                <div class="bg-white rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4">Absences by Reason</h2>
                    <canvas id="absenceReasonChart" height="100"></canvas>
                </div>

                <!-- Top Trainees -->
                <div class="bg-white rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4">Top 5 Trainees by Absences</h2>
                    <canvas id="topTraineesChart" height="150"></canvas>
                </div>

                <!-- Monthly Absence Trends Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Monthly Absence Trends</h2>
                    <canvas id="monthlyAbsenceChart" class="w-full h-64"></canvas>
                </div>

                <!-- Attendance Trends -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Weekly Attendance Trends</h2>
                    <canvas id="attendanceTrendsChart" height="100"></canvas>
                </div>


                <!-- Module Attendance Overview Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Module Attendance Overview</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr
                                    class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-2 px-4">Module</th>
                                    <th class="py-2 px-4">Trainees</th>
                                    <th class="py-2 px-4">Total Absences</th>
                                    <th class="py-2 px-4">Justified</th>
                                    <th class="py-2 px-4">Unjustified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($modulesOverview as $module)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4">{{ $module->name }}</td>
                                        <td class="py-2 px-4">{{ $module->trainees_count }}</td>
                                        <td class="py-2 px-4">{{ $module->total_absences }}</td>
                                        <td class="py-2 px-4 text-green-600">{{ $module->justified_absences }}</td>
                                        <td class="py-2 px-4 text-red-600">{{ $module->unjustified_absences }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            No module data available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Absences per Month Bar Chart
        const ctx = document.getElementById('absenceChart').getContext('2d');
        new Chart(ctx, {
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

        // Absences per Module Bar Chart
        const moduleCtx = document.getElementById('absenceModuleChart').getContext('2d');
        new Chart(moduleCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($absenceModuleLabels) !!},
                datasets: [{
                    label: 'Absences per Module',
                    data: {!! json_encode($absenceModuleData) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgba(34, 197, 94, 1)',
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

        // Monthly Absence Trends Chart
        const ctxMonthly = document.getElementById('monthlyAbsenceChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $monthlyAbsenceData->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y')),
                ) !!},
                datasets: [{
                    label: 'Absences',
                    data: {!! json_encode($monthlyAbsenceData->pluck('total')) !!},
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#111'
                        }
                    }
                }
            }
        });

        // Monthly Absence Line Chart
        const ctxLine = document.getElementById('monthlyAbsenceLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Absences',
                    data: @json($totals),
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(99, 102, 241, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // Module Absence Bar Chart
        const ctxModule = document.getElementById('moduleAbsenceBarChart').getContext('2d');
        new Chart(ctxModule, {
            type: 'bar',
            data: {
                labels: @json($moduleNames),
                datasets: [{
                    label: 'Absences',
                    data: @json($moduleTotals),
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // Justified vs Unjustified Absences Doughnut Chart
        const justifiedCtx = document.getElementById('justifiedChart').getContext('2d');
        new Chart(justifiedCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($justifiedAbsences)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($justifiedAbsences)) !!},
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Weekly Absences Bar Chart
        const weeklyCtx = document.getElementById('weeklyAbsencesChart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($weeklyAbsences)) !!},
                datasets: [{
                    label: 'Absences',
                    data: {!! json_encode(array_values($weeklyAbsences)) !!},
                    backgroundColor: '#6366F1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                return this.getLabelForValue(value).split('-').slice(1).join('/');
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Absences by Reason Pie Chart
        document.addEventListener('DOMContentLoaded', function() {
            const reasonCanvas = document.getElementById('absenceReasonChart');
            if (reasonCanvas) {
                const reasonCtx = reasonCanvas.getContext('2d');
                new Chart(reasonCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($absencesByReason)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($absencesByReason)) !!},
                            backgroundColor: [
                                '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                                '#EC4899', '#22D3EE', '#F97316'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }
        });

        const reasonCtx = document.getElementById('absenceReasonChart').getContext('2d');
        const absenceReasonChart = new Chart(reasonCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($reasonCounts)) !!},
                datasets: [{
                    label: 'Absences',
                    data: {!! json_encode(array_values($reasonCounts)) !!},
                    backgroundColor: [
                        '#6366F1', '#10B981', '#F59E0B', '#EF4444', '#3B82F6',
                        '#8B5CF6', '#F472B6', '#22D3EE'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#374151',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });


        const topTraineesCtx = document.getElementById('topTraineesChart').getContext('2d');
        const topTraineesChart = new Chart(topTraineesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topTrainees->pluck('name')) !!},
                datasets: [{
                    label: 'Absences',
                    data: {!! json_encode($topTrainees->pluck('total')) !!},
                    backgroundColor: '#6366F1',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: '#374151'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#374151'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const attendanceTrendsCtx = document.getElementById('attendanceTrendsChart').getContext('2d');
        new Chart(attendanceTrendsCtx, {
            type: 'line',
            data: {
                labels: @json($trendDays),
                datasets: [{
                        label: 'Present',
                        data: @json($presentData),
                        borderColor: 'rgb(34,197,94)',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        tension: 0.3
                    },
                    {
                        label: 'Absent',
                        data: @json($absentData),
                        borderColor: 'rgb(239,68,68)',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        tension: 0.3
                    },
                    {
                        label: 'Late',
                        data: @json($lateData),
                        borderColor: 'rgb(234,179,8)',
                        backgroundColor: 'rgba(234,179,8,0.1)',
                        tension: 0.3
                    }
                ]
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
    </script>
@endsection
