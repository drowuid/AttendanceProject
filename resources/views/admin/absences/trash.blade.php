@extends('layouts.admin')

@section('content')
<h2 class="text-xl font-bold mb-4">Trashed Absences</h2>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
@endif

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
            <td class="p-2">{{ $absence->user->name ?? '-' }}</td>
            <td class="p-2">{{ $absence->module->name ?? '-' }}</td>
            <td class="p-2">{{ $absence->date }}</td>
            <td class="p-2">{{ $absence->reason }}</td>
            <td class="p-2 flex gap-2">
                <form method="POST" action="{{ route('admin.absences.restore', $absence->id) }}">
                    @csrf @method('PUT')
                    <button class="text-green-600">Restore</button>
                </form>
                <form method="POST" action="{{ route('admin.absences.forceDelete', $absence->id) }}">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Permanently delete this?')" class="text-red-600">Delete</button>
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
