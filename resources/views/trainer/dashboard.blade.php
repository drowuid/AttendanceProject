<!DOCTYPE html>
<html>
<head>
    <title>Trainer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Chart -->
        <div class="bg-gray-50 p-6 rounded-xl shadow mb-10">
            <h2 class="text-xl font-semibold mb-4">Absences Per Module</h2>
            <canvas id="moduleAbsenceChart" height="100"></canvas>
        </div>

        <!-- Filter Form -->
<form method="GET" action="{{ route('trainer.dashboard') }}" class="mb-6 flex flex-wrap items-end gap-4">
    <div>
        <label class="block text-sm font-medium">Module Name</label>
        <input type="text" name="name" value="{{ request('name') }}" class="border rounded p-2 w-full">
    </div>
    <div>
        <label class="block text-sm font-medium">Start Date After</label>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded p-2 w-full">
    </div>
    <div class="mt-1">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </div>
</form>


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

    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('moduleAbsenceChart').getContext('2d');
        new Chart(ctx, {
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
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</body>
</html>
