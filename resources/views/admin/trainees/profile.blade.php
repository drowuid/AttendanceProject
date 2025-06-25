@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">

        <div class="container mx-auto">
            <!-- The container for both the title and the button, now integrated with your existing div -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Trainee Profile</h1>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-900 transition font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>


            <!-- Remaining profile details and statistics from your original snippet -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
                <p class="text-gray-600 dark:text-gray-300"><strong>Name:</strong> {{ $user->name }}</p>
                <p class="text-gray-600 dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-gray-600 dark:text-gray-300"><strong>Registered:</strong>
                    {{ $user->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Absences</h2>
                    <p class="text-3xl font-bold text-red-500">{{ $total }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Justified</h2>
                    <p class="text-3xl font-bold text-green-500">{{ $justified }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Unjustified</h2>
                    <p class="text-3xl font-bold text-red-700">{{ $unjustified }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Absence Records</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                <th class="py-2">
                                    <a href="{{ route('admin.trainees.profile', ['user' => $user->id, 'sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-semibold">
                                        Date
                                        @if (request('sort') === 'asc')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="py-2">Module</th>
                                <th class="py-2">Reason</th>
                                <th class="py-2">Justified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absences as $absence)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                                    <td class="py-2">{{ $absence->module->name ?? 'N/A' }}</td>
                                    <td class="py-2">{{ $absence->reason ?? '-' }}</td>
                                    <td class="py-2 flex items-center gap-2">
                                        @if ($absence->justified)
                                            <span class="text-green-600 font-medium">Yes</span>
                                        @else
                                            <span class="text-red-600 font-medium">No</span>
                                        @endif

                                        @if (Route::has('admin.absences.edit'))
                                            <a href="{{ route('admin.absences.edit', $absence->id) }}"
                                                class="ml-2 inline-block px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                                Edit
                                            </a>
                                        @else
                                            <span class="ml-2 text-xs text-gray-400">Edit route missing</span>
                                        @endif
                                    </td>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No absences
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>
            <a href="{{ route('admin.trainees.exportProfile', $user->id) }}"
                class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm font-medium">
                Export PDF
            </a>
        </div>

    </div>
@endsection
