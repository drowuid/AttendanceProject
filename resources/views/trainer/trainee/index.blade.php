@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A4 4 0 006 18h12a4 4 0 00.879-.096M16 11a4 4 0 00-8 0m8 0v0a4 4 0 00-8 0m8 0v0a4 4 0 00-8 0" />
            </svg>
        </span>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainee Profile</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Details and summary for <strong>{{ $trainee->name }}</strong></p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
            <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Total Absences</h2>
            <p class="text-3xl font-bold text-red-600">{{ $totalAbsences }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
            <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Justified Absences</h2>
            <p class="text-3xl font-bold text-green-600">{{ $justifiedAbsences }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
            <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Unjustified Absences</h2>
            <p class="text-3xl font-bold text-yellow-600">{{ $unjustifiedAbsences }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 text-center">
            <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Modules Attended</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $moduleCount }}</p>
        </div>
    </div>

    <!-- Absence Log Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 overflow-x-auto">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Absence Log</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b dark:border-gray-700 text-xs uppercase text-gray-600 dark:text-gray-300">
                    <th class="py-2">Date</th>
                    <th class="py-2">Module</th>
                    <th class="py-2">Reason</th>
                    <th class="py-2">Justified</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absences as $absence)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                        <td class="py-2">{{ $absence->module->name }}</td>
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
                        <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No absences found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
