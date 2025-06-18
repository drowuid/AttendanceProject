<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Absence Report PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #666;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Trainer Absence Report</h2>
    <table>
        <thead>
            <tr>
                <th>Trainee</th>
                <th>Module</th>
                <th>Date</th>
                <th>Justified</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absences as $absence)
                <tr>
                    <td>{{ $absence->trainee->name }}</td>
                    <td>{{ $absence->module->name }}</td>
                    <td>{{ $absence->date }}</td>
                    <td>{{ $absence->justified ? 'Yes' : 'No' }}</td>
                    <td>{{ $absence->reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
