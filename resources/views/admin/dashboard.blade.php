@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen container mx-auto p-6 gap-6">

        <!-- Sidebar -->
        <aside class="w-48 bg-white rounded-xl shadow p-4 sticky top-6 h-fit">
            <div class="mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                Quick Actions
            </div>
            <a href="{{ route('admin.absences.index') }}"
                class="block px-3 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs font-medium text-center">
                Manage Absences
            </a>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="flex items-center gap-3 mb-8">
                <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600 dark:text-red-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h18v18H3V3zm3 6h12v2H6V9zm0 4h12v2H6v-2z" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Admin Dashboard</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-base mt-1">
                        Overview of platform activity and stats.
                    </p>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Total Users</h2>
                    <p class="text-4xl font-bold text-blue-600">{{ $userCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Total Trainers</h2>
                    <p class="text-4xl font-bold text-purple-600">{{ $trainerCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Total Trainees</h2>
                    <p class="text-4xl font-bold text-green-600">{{ $traineesCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Absence Records</h2>
                    <p class="text-4xl font-bold text-red-600">{{ $absenceCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Marked Absences</h2>
                    <p class="text-4xl font-bold text-red-500">{{ $absencesCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Modules</h2>
                    <p class="text-4xl font-bold text-yellow-600">{{ $moduleCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Courses</h2>
                    <p class="text-4xl font-bold text-indigo-600">{{ $courseCount }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">Active Sessions</h2>
                    <p class="text-4xl font-bold text-gray-700">{{ $activeSessions ?? 0 }}</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 h-80">
                    <h2 class="text-xl font-semibold mb-4">Absences per Month</h2>
                    <div class="relative h-64 w-full">
                        <canvas id="absenceChart" class="w-full h-full"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 h-80">
                    <h2 class="text-xl font-semibold mb-4">Absences per Module</h2>
                    <div class="relative h-64 w-full">
                        <canvas id="absenceModuleChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
            <!-- User Management Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mt-10">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Recent Users</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
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
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No recent
                                        users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Trainee Absences -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mt-10">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Recent Trainee Absences</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
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
                                    <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
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
                                    <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No absences
                                        recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Absence Trends Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mt-10">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Monthly Absence Trends</h2>
                <canvas id="monthlyAbsenceChart" class="w-full h-64"></canvas>
            </div>

        </main>




    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
    const monthlyChart = new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyAbsenceData->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m.'-01')->format('M Y'))) !!},
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

    </script>
@endsection
