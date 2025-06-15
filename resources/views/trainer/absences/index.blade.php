@extends('layouts.trainer')

@section('content')
<h2 class="text-xl font-bold mb-4">Your Module Absences</h2>

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
                <a href="{{ route('trainer.absences.show', $absence) }}" class="text-blue-600">View</a>
                <a href="{{ route('trainer.absences.edit', $absence) }}" class="text-yellow-600">Edit</a>
                <form method="POST" action="{{ route('trainer.absences.destroy', $absence) }}" class="inline-block" onsubmit="return confirm('Delete this absence?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Delete</button>

                    <a href="{{ route('trainer.absences.export', request()->query()) }}"
   class="bg-green-600 text-white px-4 py-2 rounded">
   Export PDF
</a>

<a href="{{ route('trainer.absences.calendar') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
    📅 View Absence Calendar
</a>
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
