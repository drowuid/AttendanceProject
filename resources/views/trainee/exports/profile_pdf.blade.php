<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile Summary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        h1 { font-size: 20px; }
    </style>
</head>
<body>
    <h1>Profile Summary - {{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>

    <h2>Assigned Modules</h2>
    <table>
        <thead>
            <tr>
                <th>Module</th>
                <th>Trainer</th>
                <th>Hours</th>
                <th>Start</th>
                <th>End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
                <tr>
                    <td>{{ $module->name }}</td>
                    <td>{{ optional($module->trainer)->name ?? 'N/A' }}</td>
                    <td>{{ $module->hours }}</td>
                    <td>{{ $module->start_date }}</td>
                    <td>{{ $module->end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Absences</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Module</th>
                <th>Reason</th>
                <th>Justified</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
                <tr>
                    <td>{{ $absence->date }}</td>
                    <td>{{ optional($absence->module)->name ?? 'N/A' }}</td>
                    <td>{{ $absence->reason }}</td>
                    <td>{{ $absence->justified ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
