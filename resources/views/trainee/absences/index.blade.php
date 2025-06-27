@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto">

        <div class="container mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4"> <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Absences</h1>
            <a href="{{ route('trainee.dashboard') }}"
               class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Back to Dashboard
            </a>
        </div>
        </div>
</div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 text-sm mb-1">Justification</label>
                    <select name="justified" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" {{ request('justified', '') === '' ? 'selected' : '' }}>All</option>
                        <option value="1" {{ request('justified') === '1' ? 'selected' : '' }}>Justified</option>
                        <option value="0" {{ request('justified') === '0' ? 'selected' : '' }}>Unjustified</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 text-sm mb-1">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 text-sm mb-1">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        @if ($absences->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-gray-500 dark:text-gray-400">
                No absences found.
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                            <th class="py-2">Date</th>
                            <th class="py-2">Module</th>
                            <th class="py-2">Reason</th>
                            <th class="py-2">Justified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                                <td class="py-2">{{ optional($absence->module)->name ?? 'N/A' }}</td>
                                <td class="py-2">{{ $absence->reason ?? '-' }}</td>
                                <td class="py-2">
                                    <span class="{{ $absence->justified ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $absence->justified ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Download Button -->
        <div class="mt-4">
            <a href="{{ route('trainee.absences.export.csv') }}"
               class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Download All Absences CSV
            </a>
        </div>
    </div>
</div>
@endsection
