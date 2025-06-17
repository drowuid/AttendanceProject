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
        @foreach($absences as $absence)
            <tr>
                <td>{{ $absence->attendance->trainee->name ?? '-' }}</td>
                <td>{{ $absence->module->name ?? '-' }}</td>
                <td>{{ $absence->created_at->format('Y-m-d') }}</td>
                <td>{{ $absence->justified ? 'Yes' : 'No' }}</td>
                <td>{{ $absence->reason }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
