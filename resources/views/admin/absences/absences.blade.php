@extends('layouts.admin')

@section('content')
<h2 class="text-xl font-bold mb-4">Absence List</h2>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr>
            <th class="p-2">User</th>
            <th class="p-2">Module</th>
            <th class="p-2">Date</th>
            <th class="p-2">Reason</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($absences as $absence)
        <tr class="border-t">
            <td class="p-2">{{ $absence->user->name }}</td>
            <td class="p-2">{{ $absence->module->name }}</td>
            <td class="p-2">{{ $absence->date }}</td>
            <td class="p-2">{{ $absence->reason }}</td>
            <td class="p-2 flex gap-2">
                <a href="{{ route('admin.absences.show', $absence) }}" class="text-blue-600">View</a>
                <a href="{{ route('admin.absences.edit', $absence) }}" class="text-yellow-600">Edit</a>
                <form method="POST" action="{{ route('admin.absences.destroy', $absence) }}">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this absence?')" class="text-red-600">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $absences->links() }}
</div>
@endsection
