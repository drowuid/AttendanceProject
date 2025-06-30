@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto max-w-4xl space-y-6">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Trainee Profile</h2>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                <p><strong>Name:</strong> {{ $trainee->name }}</p>
                <p><strong>Email:</strong> {{ $trainee->email }}</p>
                <p><strong>Registered:</strong> {{ $trainee->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Modules</h3>
            @forelse ($trainee->modules as $module)
                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $module->name }}</div>
            @empty
                <div class="text-sm text-gray-400">No modules found.</div>
            @endforelse
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Absence Summary</h3>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <li>Total Absences: <strong>{{ $total }}</strong></li>
                <li>Justified: <span class="text-green-600">{{ $justified }}</span></li>
                <li>Unjustified: <span class="text-red-600">{{ $unjustified }}</span></li>
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Absences Details</h3>
            @if ($absences->isEmpty())
                <p class="text-gray-500">No absences recorded.</p>
            @else
                <table class="min-w-full text-xs text-left">
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
                                <td class="py-2">{{ $absence->module->name ?? 'Unknown' }}</td>
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
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('trainer.dashboard') }}"
               class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
