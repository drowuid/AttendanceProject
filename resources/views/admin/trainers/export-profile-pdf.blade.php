<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainer Profile PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Trainer Profile: {{ $user->name }}</h1>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    <h2>Assigned Modules</h2>
    @if($modules->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Hours</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr>
                        <td>{{ $module->name }}</td>
                        <td>{{ $module->hours }}h</td>
                        <td>{{ \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No modules assigned.</p>
    @endif

    <h2>Absences Logged</h2>
    @if($absences->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Trainee</th>
                    <th>Module</th>
                    <th>Justified</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absences as $absence)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                        <td>{{ $absence->user->name ?? 'N/A' }}</td>
                        <td>{{ $absence->module->name ?? 'N/A' }}</td>
                        <td>{{ $absence->justified ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No absences logged by this trainer.</p>
    @endif
</body>
</html>
