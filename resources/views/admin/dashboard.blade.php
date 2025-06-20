@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen container mx-auto p-6 gap-6">

        <!-- Sidebar -->
        <aside class="w-64 bg-white rounded-2xl shadow p-6 sticky top-6 h-fit">
            <div class="mb-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Quick Actions
            </div>
            <div class="flex flex-wrap gap-4 mb-8">
                <a href="{{ route('admin.absences.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-sm font-medium">
                    Manage Absences
                </a>
            </div>
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
    </script>
@endsection
