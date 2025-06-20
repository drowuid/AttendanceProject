@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Absence Reports</h1>
        <div class="flex gap-2">
            <a href="{{ route('trainer.reports.export.pdf', request()->query()) }}"
   class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm font-medium">
   Export to PDF
</a>

<a href="{{ route('trainer.absence.email.summary') }}"
   class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-medium ml-2">
   Export Email Summary
</a>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap gap-4 mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
        <select name="module_id" class="border p-2 rounded w-48 dark:bg-gray-900 dark:border-gray-700">
            <option value="">All Modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded dark:bg-gray-900 dark:border-gray-700">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded dark:bg-gray-900 dark:border-gray-700">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>

        <a href="{{ route('trainer.export.absences') }}" class="ml-auto bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Export to Excel
        </a>
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