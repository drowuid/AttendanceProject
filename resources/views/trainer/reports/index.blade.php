@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Absence Reports</h1>
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-4 mb-6">
    <div>
        <label for="module_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modules</label>
        <select name="module_id[]" multiple class="border p-2 rounded w-52 h-28 overflow-y-auto text-sm">
            @foreach($modules as $module)
                <option value="{{ $module->id }}"
                    {{ collect(request('module_id'))->contains($module->id) ? 'selected' : '' }}>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded">
    </div>

    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>

        <a href="{{ route('trainer.reports') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Clear
        </a>
    </div>

    <div class="ml-auto flex gap-2">
        <a href="{{ route('reports.export.pdf', request()->query()) }}"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Export to PDF
        </a>

        <a href="{{ route('trainer.export.absences') }}"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Export to Excel
        </a>
    </div>
</form>


    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b dark:border-gray-700">
                    <th class="py-2">Trainee</th>
                    <th class="py-2">Module</th>
                    <th class="py-2">Date</th>
                    <th class="py-2">Reason</th>
                    <th class="py-2">Justified</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absences as $absence)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">{{ $absence->trainee->name }}</td>
                    <td class="py-2">{{ $absence->module->name }}</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                    <td class="py-2">{{ $absence->reason }}</td>
                    <td class="py-2">
                        @if ($absence->justified)
                            <span class="text-green-600 font-medium">Yes</span>
                        @else
                            <span class="text-red-600 font-medium">No</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">No absences found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $absences->withQueryString()->links() }}
    </div>
</div>
@endsection
