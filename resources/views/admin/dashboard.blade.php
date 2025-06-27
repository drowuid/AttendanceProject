{{-- filepath: c:\SoftwareDev2025Laravel\AttendanceProject\AttendanceProject\resources\views\admin\dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="container mx-auto flex flex-col lg:flex-row gap-6">

            <!-- Sidebar -->
            <aside
                class="w-full lg:w-48 bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6 lg:mb-0 lg:sticky top-8 h-fit flex flex-col gap-3 text-xs">
                <div>
                    <div class="mb-1 font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Quick Actions
                    </div>
                    <a href="{{ route('admin.absences.index') }}"
                        class="block px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs font-medium text-center transition-all duration-150">
                        Manage Absences
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="block px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs font-medium text-center transition-all duration-150 mt-1">
                        Manage Users & Roles
                    </a>
                    <a href="{{ route('admin.admin.trainees.index') }}"
                        class="block px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs font-medium text-center transition-all duration-150 mt-1">
                        Manage Trainees
                    </a>
                </div>
                <div>
                    <div class="mb-1 font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Export Options
                    </div>
                    <a href="{{ route('admin.modules.export') }}"
                        class="block px-2 py-1 text-xs font-medium text-white bg-green-500 hover:bg-green-600 rounded text-center transition-all duration-150 mb-1">
                        Modules
                    </a>
                    <a href="{{ route('admin.admin.export.topTrainees') }}"
                        class="block px-2 py-1 text-xs font-medium text-white bg-purple-500 hover:bg-purple-600 rounded text-center transition-all duration-150 mb-1">
                        Top Trainees
                    </a>
                    <a href="{{ route('admin.admin.export.absencesByReason') }}"
                        class="block px-2 py-1 text-xs font-medium text-white bg-pink-500 hover:bg-pink-600 rounded text-center transition-all duration-150">
                        Absences by Reason
                    </a>
                    <a href="{{ route('admin.admin.export.justifiedVsUnjustified') }}"
                        class="block px-2 py-1 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded text-center transition-all duration-150 mt-1">
                        Justified/Unjustified
                    </a>
                    <a href="{{ route('admin.admin.export.weeklyAbsences') }}"
                        class="block px-2 py-1 text-xs font-medium text-white bg-red-500 hover:bg-yellow-600 rounded text-center transition-all duration-150 mt-1">
                        Weekly Absences
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col gap-5">
                <!-- Header -->
                <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 dark:bg-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h18v18H3V3zm3 6h12v2H6V9zm0 4h12v2H6v-2z" />
                        </svg>
                    </span>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">Admin Dashboard</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                            Overview of platform activity and stats.
                        </p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Total Users</h2>
                        <p class="text-2xl font-bold text-blue-600">{{ $userCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Total Trainers</h2>
                        <p class="text-2xl font-bold text-purple-600">{{ $trainerCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Total Trainees</h2>
                        <p class="text-2xl font-bold text-green-600">{{ $traineesCount }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 text-center">
                        <h2 class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-0.5">Absence Records</h2>
                        <p class="text-2xl font-bold text-red-600">{{ $absenceCount }}</p>
                    </div>
                </div>

                <!-- User Management Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-white">Recent Users</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-left">
                            <thead>
                                <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-1">Name</th>
                                    <th class="py-1">Email</th>
                                    <th class="py-1">Role</th>
                                    <th class="py-1">Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $user)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td>
                                            @if ($user->role === 'trainee')
                                                <a href="{{ route('admin.trainees.profile', $user->id) }}" class="text-blue-600 hover:underline font-medium">
                                                    {{ $user->name }}
                                                </a>
                                            @elseif ($user->role === 'trainer')
                                                <a href="{{ route('admin.trainers.profile', $user->id) }}" class="text-blue-600 hover:underline font-medium">
                                                    {{ $user->name }}
                                                </a>
                                            @else
                                                {{ $user->name }}
                                            @endif
                                        </td>
                                        <td class="py-1">{{ $user->email }}</td>
                                        <td class="py-1 capitalize">{{ $user->role }}</td>
                                        <td class="py-1">{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-2 text-gray-500 dark:text-gray-400">
                                            No recent users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Trainee Absences -->
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
                                @forelse ($recentAbsentees as $absence)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-1">
                                            @if ($absence->trainee)
                                                <a href="{{ route('admin.trainees.profile', ['user' => $absence->trainee->id]) }}"
                                                   class="text-blue-600 hover:underline">
                                                    {{ $absence->trainee->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-500">Unknown</span>
                                            @endif
                                        </td>
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
                                        <td colspan="4" class="py-2 text-center text-gray-500 dark:text-gray-400">No absences recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Monthly Absences Line Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Monthly Absences</h2>
                        <canvas id="monthlyAbsenceLineChart" width="220" height="120"></canvas>
                    </div>
                    <!-- Absences Per Module Bar Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Absences Per Module</h2>
                        <canvas id="moduleAbsenceBarChart" width="220" height="120"></canvas>
                    </div>
                    <!-- Absences per Month Bar Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Absences per Month</h2>
                        <canvas id="absenceChart" width="220" height="120"></canvas>
                    </div>
                    <!-- Absences per Module -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Absences per Module</h2>
                        <canvas id="absenceModuleChart" width="220" height="120"></canvas>
                    </div>
                    <!-- Justified vs Unjustified Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Justified vs Unjustified</h2>
                        <canvas id="justifiedChart" width="180" height="100"></canvas>
                    </div>
                    <!-- Weekly Absences -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Weekly Absences</h2>
                        <canvas id="weeklyAbsencesChart" width="180" height="100"></canvas>
                    </div>
                    <!-- Absences by Reason -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Absences by Reason</h2>
                        <canvas id="absenceReasonChart" width="180" height="100"></canvas>
                    </div>
                    <!-- Top Trainees -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Top 5 Trainees by Absences</h2>
                        <canvas id="topTraineesChart" width="180" height="100"></canvas>
                    </div>
                    <!-- Monthly Absence Trends Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Monthly Absence Trends</h2>
                        <canvas id="monthlyAbsenceChart" width="220" height="120"></canvas>
                    </div>
                    <!-- Attendance Trends -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col items-center">
                        <h2 class="text-sm font-semibold mb-2 text-gray-900 dark:text-white">Weekly Attendance Trends</h2>
                        <canvas id="attendanceTrendsChart" width="220" height="120"></canvas>
                    </div>
                </div>

                <!-- Module Attendance Overview Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mt-4">
                    <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-white">Module Attendance Overview</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-left">
                            <thead>
                                <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-1 px-2">Module</th>
                                    <th class="py-1 px-2">Trainees</th>
                                    <th class="py-1 px-2">Total Absences</th>
                                    <th class="py-1 px-2">Justified</th>
                                    <th class="py-1 px-2">Unjustified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($modulesOverview as $module)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-1 px-2">{{ $module->name }}</td>
                                        <td class="py-1 px-2">{{ $module->trainees_count }}</td>
                                        <td class="py-1 px-2">{{ $module->total_absences }}</td>
                                        <td class="py-1 px-2 text-green-600">{{ $module->justified_absences }}</td>
                                        <td class="py-1 px-2 text-red-600">{{ $module->unjustified_absences }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-2 text-gray-500 dark:text-gray-400">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Common chart options with fixed dimensions
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                // Set fixed canvas size
                aspectRatio: 2, // width/height ratio
            };

            // Set container styles for consistent sizing
            function setChartContainer(id, width = 350, height = 180) {
                const canvas = document.getElementById(id);
                if (canvas) {
                    // Set canvas attributes
                    canvas.width = width;
                    canvas.height = height;

                    // Create or get parent container
                    let container = canvas.parentElement;
                    if (!container.classList.contains('chart-container')) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'chart-container';
                        wrapper.style.cssText = `width: ${width}px; height: ${height}px; position: relative;`;
                        canvas.parentNode.insertBefore(wrapper, canvas);
                        wrapper.appendChild(canvas);
                        container = wrapper;
                    }

                    // Set container dimensions
                    container.style.width = width + 'px';
                    container.style.height = height + 'px';
                    container.style.position = 'relative';
                }
            }

            // Apply container sizing to all charts
            const chartConfigs = [
                { id: 'absenceChart', width: 350, height: 180 },
                { id: 'absenceModuleChart', width: 350, height: 180 },
                { id: 'monthlyAbsenceChart', width: 350, height: 180 },
                { id: 'monthlyAbsenceLineChart', width: 350, height: 180 },
                { id: 'moduleAbsenceBarChart', width: 350, height: 180 },
                { id: 'justifiedChart', width: 350, height: 180 },
                { id: 'weeklyAbsencesChart', width: 350, height: 180 },
                { id: 'absenceReasonChart', width: 350, height: 180 },
                { id: 'topTraineesChart', width: 350, height: 180 },
                { id: 'attendanceTrendsChart', width: 350, height: 180 }
            ];

            chartConfigs.forEach(config => {
                setChartContainer(config.id, config.width, config.height);
            });

            // Absences per Month Bar Chart
            const ctx = document.getElementById('absenceChart');
            if (ctx) {
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
                        ...chartOptions,
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

            // Absences per Module Bar Chart
            const moduleCtx = document.getElementById('absenceModuleChart');
            if (moduleCtx) {
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
                        ...chartOptions,
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

            // Monthly Absence Trends Chart
            const ctxMonthly = document.getElementById('monthlyAbsenceChart');
            if (ctxMonthly) {
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
                        ...chartOptions,
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
            }

            // Monthly Absence Line Chart
            const ctxLine = document.getElementById('monthlyAbsenceLineChart');
            if (ctxLine) {
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
                        ...chartOptions,
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
            }

            // Module Absence Bar Chart
            const ctxModule = document.getElementById('moduleAbsenceBarChart');
            if (ctxModule) {
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
                        ...chartOptions,
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
            }

            // Justified vs Unjustified Absences Doughnut Chart
            const justifiedCtx = document.getElementById('justifiedChart');
            if (justifiedCtx) {
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
                        ...chartOptions,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Weekly Absences Bar Chart
            const weeklyCtx = document.getElementById('weeklyAbsencesChart');
            if (weeklyCtx) {
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
                        ...chartOptions,
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
            }

            // Absences by Reason Chart
            const reasonCtx = document.getElementById('absenceReasonChart');
            if (reasonCtx) {
                new Chart(reasonCtx, {
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
                        ...chartOptions,
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
            }

            // Top Trainees Chart
            const topTraineesCtx = document.getElementById('topTraineesChart');
            if (topTraineesCtx) {
                new Chart(topTraineesCtx, {
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
                        ...chartOptions,
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
            }

            // Attendance Trends Chart
            const attendanceTrendsCtx = document.getElementById('attendanceTrendsChart');
            if (attendanceTrendsCtx) {
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
                        ...chartOptions,
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
        });
    </script>
@endsection

