@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto">

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome, {{ $user->name }}</h1>
        </div>

        <!-- Absence Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Absences</h2>
                <p class="text-3xl font-bold text-red-500">{{ $totalAbsences }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Justified</h2>
                <p class="text-3xl font-bold text-green-500">{{ $justified }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Unjustified</h2>
                <p class="text-3xl font-bold text-red-700">{{ $unjustified }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Attendance Rate</h2>
                <p class="text-3xl font-bold text-blue-600">
                    {{ $attendanceRate !== null ? $attendanceRate . '%' : 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Assigned Modules -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Your Modules</h2>
            @if ($modules->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No modules assigned yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                <th class="py-2">Module</th>
                                <th class="py-2">Trainer</th>
                                <th class="py-2">Hours</th>
                                <th class="py-2">Start</th>
                                <th class="py-2">End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-2">{{ $module->name }}</td>
                                    <td class="py-2">{{ optional($module->trainer)->name ?? 'N/A' }}</td>
                                    <td class="py-2">{{ $module->hours }}h</td>
                                    <td class="py-2">{{ $module->start_date ? \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="py-2">{{ $module->end_date ? \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Absences -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Absence History</h2>
            @if ($absences->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No absences recorded.</p>
            @else
                <div class="overflow-x-auto">
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
                                    <td class="py-2">{{ $absence->date ? \Carbon\Carbon::parse($absence->date)->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="py-2">{{ optional($absence->module)->name ?? 'N/A' }}</td>
                                    <td class="py-2">{{ $absence->reason ?? '-' }}</td>
                                    <td class="py-2">
                                        <span class="{{ $absence->is_excused ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $absence->is_excused ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
