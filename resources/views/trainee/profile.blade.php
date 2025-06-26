@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-10">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">My Profile</h1>

            <div class="flex gap-3 mb-4">
                <a href="{{ route('trainee.profile.export.pdf') }}"
                   class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                    Download PDF
                </a>
                <a href="{{ route('trainee.modules.index') }}"
                   class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    View My Modules
                </a>
                <div class="flex-1"></div>
                <a href="{{ route('trainee.dashboard') }}"
                   class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ml-auto">
                    Back to Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Personal Info</h2>
                    <p class="text-gray-600 dark:text-gray-300"><strong>Name:</strong> {{ $user->name }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-gray-600 dark:text-gray-300"><strong>Course:</strong> {{ optional($user->course)->title ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Absence Summary</h2>
                    <p><strong>Total:</strong> <span class="text-red-600">{{ $totalAbsences }}</span></p>
                    <p><strong>Justified:</strong> <span class="text-green-600">{{ $justified }}</span></p>
                    <p><strong>Unjustified:</strong> <span class="text-red-700">{{ $unjustified }}</span></p>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Assigned Modules</h2>
                @if ($modules->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No modules assigned.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-2">Module</th>
                                    <th class="py-2">Trainer</th>
                                    <th class="py-2">Start</th>
                                    <th class="py-2">End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modules as $module)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2">{{ $module->name }}</td>
                                        <td class="py-2">{{ optional($module->trainer)->name ?? 'N/A' }}</td>
                                        <td class="py-2">{{ \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') }}</td>
                                        <td class="py-2">{{ \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
