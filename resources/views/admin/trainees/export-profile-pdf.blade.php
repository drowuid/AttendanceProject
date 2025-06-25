<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainee Profile Export</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h1>Trainee Profile</h1>
    <p><strong>Name:</strong> {{ $trainee->name }}</p>
    <p><strong>Email:</strong> {{ $trainee->email }}</p>
    <p><strong>Course:</strong> {{ $trainee->course->name ?? 'N/A' }}</p>

    <h2>Modules</h2>
    <ul>
        @foreach($trainee->modules as $module)
            <li>{{ $module->name }}</li>
        @endforeach
    </ul>

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
            @foreach($trainee->absences as $absence)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                    <td>{{ $absence->module->name ?? 'N/A' }}</td>
                    <td>{{ $absence->reason ?? '-' }}</td>
                    <td>{{ $absence->justified ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
