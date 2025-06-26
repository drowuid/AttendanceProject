@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Trainer Profile</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Back to Dashboard</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> {{ $user->name }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <a href="{{ route('admin.trainers.exportModules', $user->id) }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Export Modules CSV</a>
            <a href="{{ route('admin.trainers.exportProfile', $user->id) }}"
               class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Export Profile PDF</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Assigned Modules</h2>
            @if ($modules->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No modules assigned to this trainer.</p>
            @else
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                            <th class="py-2">Module</th>
                            <th class="py-2">Hours</th>
                            <th class="py-2">Start Date</th>
                            <th class="py-2">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modules as $module)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-2">{{ $module->name }}</td>
                                <td class="py-2">{{ $module->hours }}h</td>
                                <td class="py-2">{{ \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') }}</td>
                                <td class="py-2">{{ \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Absences Logged by Trainer</h2>
            @if ($absences->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No absences logged by this trainer.</p>
            @else
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                            <th class="py-2">Date</th>
                            <th class="py-2">Trainee</th>
                            <th class="py-2">Module</th>
                            <th class="py-2">Justified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absences as $absence)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                                <td class="py-2">{{ $absence->user->name ?? 'N/A' }}</td>
                                <td class="py-2">{{ $absence->module->name ?? 'N/A' }}</td>
                                <td class="py-2">
                                    <span class="{{ $absence->justified ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $absence->justified ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
