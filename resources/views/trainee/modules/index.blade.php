@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Modules Overview</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Total Modules: {{ $totalModules }} | Total Hours: {{ $totalHours }}
                </p>
            </div>
            <a href="{{ route('trainee.profile') }}"
               class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Back to Profile
            </a>
        </div>
        

        <div class="flex gap-3 mb-4">
            <a href="{{ route('trainee.profile.export.csv') }}"
               class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Download Modules CSV
            </a>
        </div>

        @if ($modules->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-gray-500 dark:text-gray-400">
                No modules assigned.
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
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
</div>
@endsection
