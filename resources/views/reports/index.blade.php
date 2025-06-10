<!DOCTYPE html>
<html>
<head>
    <title>Attendance Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow p-6">

        <h1 class="text-3xl font-bold mb-6">Attendance Reports</h1>

        <form method="GET" action="{{ route('reports.index') }}" class="mb-6 flex gap-4 items-end">
            <div>
                <label class="block mb-1 font-semibold" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="border rounded p-2">
            </div>
            <div>
                <label class="block mb-1 font-semibold" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="border rounded p-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('reports.index') }}" class="ml-2 text-gray-600 underline">Clear</a>
        </form>

        @if($modules->isEmpty())
            <p>No reports found for the selected criteria.</p>
        @else
            @foreach($modules as $module)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold">{{ $module->name }} ({{ $module->start_date }} - {{ $module->end_date }})</h2>

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
                                    $trainee = $records->first() ? $records->first()->trainee : null;
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
                                <tr class="border-t">
                                    <td class="p-2">{{ $trainee ? $trainee->name : 'Unknown' }}</td>
                                    <td class="p-2">{{ $present }}</td>
                                    <td class="p-2">{{ $absent }}</td>
                                    <td class="p-2">{{ $late }}</td>
                                    <td class="p-2">{{ number_format($hours, 2) }}h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

</body>
</html>
