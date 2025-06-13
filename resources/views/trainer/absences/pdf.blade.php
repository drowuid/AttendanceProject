<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Absences Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Absences Report</h2>
    <table>
        <thead>
            <tr>
                <th>Trainee</th>
                <th>Module</th>
                <th>Date</th>
                <th>Excused</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
            <tr>
                <td>{{ $absence->user->name }}</td>
                <td>{{ $absence->module->name }}</td>
                <td>{{ $absence->date }}</td>
                <td>{{ $absence->is_excused ? 'Yes' : 'No' }}</td>
                <td>{{ $absence->reason ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>