<!DOCTYPE html>
<html>
<head>
    <title>Trainer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Trainer Dashboard</h1>

<a href="{{ route('calendar.index') }}" class="text-blue-600 underline text-sm mb-4 inline-block">View Course Calendar</a>
<a href="{{ route('trainer.reports') }}" class="text-blue-600 underline">View Reports</a>



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
</body>
</html>
