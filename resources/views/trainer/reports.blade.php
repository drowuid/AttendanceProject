<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Attendance Reports</h1>

        @if(count($traineeStats) > 0)
            <table class="min-w-full text-sm border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Trainee</th>
                        <th class="p-2">Module</th>
                        <th class="p-2">Present</th>
                        <th class="p-2">Late</th>
                        <th class="p-2">Absent</th>
                        <th class="p-2">Total Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traineeStats as $stat)
                        <tr class="border-t">
                            <td class="p-2">{{ $stat['trainee'] }}</td>
                            <td class="p-2">{{ $stat['module'] }}</td>
                            <td class="p-2">{{ $stat['present'] }}</td>
                            <td class="p-2">{{ $stat['late'] }}</td>
                            <td class="p-2">{{ $stat['absent'] }}</td>
                            <td class="p-2">{{ $stat['hours'] }}h</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No attendance records found.</p>
        @endif
    </div>
</body>
</html>
