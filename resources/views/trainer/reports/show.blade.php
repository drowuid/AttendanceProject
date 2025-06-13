@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-6">Module Report: {{ $module->name }}</h1>

    <div class="mb-4 text-gray-600">
        <p><strong>Dates:</strong> {{ \Carbon\Carbon::parse($module->start_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($module->end_date)->format('d M Y') }}</p>
        <p><strong>Total Trainees:</strong> {{ $trainees->count() }}</p>
    </div>

    @if($trainees->isEmpty())
        <p class="text-gray-500">No trainees found for this module.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Trainee Name</th>
                        <th class="px-4 py-2 text-left">Total Entries</th>
                        <th class="px-4 py-2 text-left">Total Exits</th>
                        <th class="px-4 py-2 text-left">Absences</th>
                        <th class="px-4 py-2 text-left">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainees as $trainee)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $trainee->name }}</td>
                            <td class="px-4 py-2">{{ $trainee->entry_count }}</td>
                            <td class="px-4 py-2">{{ $trainee->exit_count }}</td>
                            <td class="px-4 py-2">{{ $trainee->absence_count }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('trainer.traineeReport.detail', [$module->id, $trainee->id]) }}"
                                   class="text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('trainer.moduleReports.index') }}" class="text-blue-600 hover:underline">
            ← Back to Module Reports
        </a>
    </div>
</div>
@endsection
