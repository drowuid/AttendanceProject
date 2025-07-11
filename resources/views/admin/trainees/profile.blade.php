@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="container mx-auto">
            <!-- Header -->
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

            <!-- Profile Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
                <p class="text-gray-600 dark:text-gray-300"><strong>Name:</strong> {{ $user->name }}</p>
                <p class="text-gray-600 dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-gray-600 dark:text-gray-300"><strong>Registered:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
            </div>

            <!-- Absence Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-6">
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
                @if (!is_null($attendanceRate))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Attendance Rate</h2>
                        <p class="text-3xl font-bold text-blue-600">{{ $attendanceRate }}%</p>
                    </div>
                @endif
            </div>

            <!-- Absences Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
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
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                                        <span class="{{ $absence->justified ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $absence->justified ? 'Yes' : 'No' }}
                                        </span>
                                        <a href="{{ route('admin.absences.edit', $absence->id) }}"
                                            class="ml-2 inline-block px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No absences found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Assigned Modules -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Assigned Modules</h2>
@if(session('success'))
    <div class="mb-4 text-green-600 font-semibold">
        {{ session('success') }}
    </div>
@endif
                <form method="GET" class="mb-4 flex items-center gap-3 flex-wrap">
                    <input type="text" name="module" value="{{ request('module') }}" placeholder="Search by module name"
                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-1.5 shadow-sm" />
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-1.5 text-sm rounded hover:bg-indigo-700 transition min-w-[120px]">
                        Filter
                    </button>
                    @if(request('module'))
                        <a href="{{ route('admin.trainees.profile', $user->id) }}"
                            class="text-sm text-gray-600 dark:text-gray-300 underline ml-2">Clear</a>
                    @endif
                    <a href="{{ route('admin.admin.trainees.exportModules', $user->id) }}"
                        class="inline-block px-4 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm font-medium min-w-[120px] text-center">
                        Export Modules CSV
                    </a>
                    <a href="{{ route('admin.trainees.exportProfile', $user->id) }}"
                        class="inline-block px-4 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm font-medium">
                        Export PDF
                    </a>
                </form>

                @if ($modules->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead>
                                <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                                    <th class="py-2">Module</th>
                                    <th class="py-2">Trainer</th>
                                    <th class="py-2">Hours</th>
                                    <th class="py-2">Start Date</th>
                                    <th class="py-2">End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modules as $module)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="py-2">{{ $module->name }}</td>
                                        <td class="py-2">{{ $module->trainer->name ?? 'N/A' }}</td>
                                        <td class="py-2">{{ $module->hours }}h</td>
                                        <td class="py-2">{{ \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') }}</td>
                                        <td class="py-2">{{ \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No modules found for this trainee’s course.</p>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mt-8">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Manage Assigned Modules</h2>

    <form method="POST" action="{{ route('admin.admin.trainees.assignModules', $user->id) }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($availableModules as $module)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                        {{ in_array($module->id, $modules->pluck('id')->toArray()) ? 'checked' : '' }}
                        class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <span class="text-gray-700 dark:text-gray-300">{{ $module->name }} ({{ $module->trainer->name ?? 'N/A' }})</span>
                </label>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm font-medium">
                Save Assigned Modules
            </button>
        </div>
    </form>
</div>

        </div>
    </div>
@endsection

