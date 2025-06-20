@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-8">
        <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.732 6.879 1.996M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </span>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $trainee->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-base mt-1">Trainee Profile Overview</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 col-span-1">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">General Info</h2>
            <p><strong>Email:</strong> {{ $trainee->email ?? '—' }}</p>
            <p><strong>Course:</strong> {{ $trainee->course->name ?? '—' }}</p>
            <p><strong>Modules:</strong> {{ implode(', ', $modules) ?: '—' }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Recent Absences</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b dark:border-gray-700 text-xs uppercase text-gray-600 dark:text-gray-300">
                        <th class="py-2">Module</th>
                        <th class="py-2">Date</th>
                        <th class="py-2">Reason</th>
                        <th class="py-2">Justified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absences as $absence)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
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
                            <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No recent absences found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
