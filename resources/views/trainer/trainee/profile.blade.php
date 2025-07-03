@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-8">
        <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </span>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainee Profile</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Details and summary for <strong>{{ $trainee->name }}</strong></p>
        </div>
    </div>

    <!-- Trainee Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Trainee Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Name</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $trainee->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $trainee->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Registered</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $trainee->created_at->format('d/m/Y') }}</p>
            </div>
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

    <!-- Modules Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Enrolled Modules</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($modules as $module)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                    <p class="font-medium text-gray-900 dark:text-white">{{ $module->name }}</p>
                    @if($module->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $module->description }}</p>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-4">
                    <p class="text-gray-500 dark:text-gray-400">No modules found.</p>
                </div>
            @endforelse
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
                        <td class="py-2">{{ $absence->module->name ?? 'Unknown' }}</td>
                        <td class="py-2">{{ $absence->reason ?? '-' }}</td>
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

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('trainer.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
