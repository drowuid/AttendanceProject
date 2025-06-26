@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Trainees List</h1>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-900 transition font-semibold">
                ← Back to Dashboard
            </a>
        </div>
        <form method="GET" class="mb-6 flex items-center flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email"
        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm" />

    <select name="course"
        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm shadow-sm">
        <option value="">All Courses</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" @selected(request('course') == $course->id)>
                {{ $course->title }}
            </option>
        @endforeach
    </select>

    <button type="submit"
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
        Filter
    </button>

    @if(request('search') || request('course'))
        <a href="{{ route('admin.admin.trainees.index') }}"
            class="text-sm text-gray-600 dark:text-gray-300 underline ml-2">Clear Filters</a>
    @endif
</form>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="text-xs uppercase text-gray-600 dark:text-gray-300 border-b dark:border-gray-700">
                        <th class="py-2">Name</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Course</th>
                        <th class="py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainees as $trainee)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2">{{ $trainee->name }}</td>
                            <td class="py-2">{{ $trainee->email }}</td>
                            <td class="py-2">{{ $trainee->course->title ?? 'N/A' }}</td>
                            <td class="py-2 text-center">
                                <a href="{{ route('admin.trainees.profile', $trainee->id) }}"
                                   class="inline-block px-3 py-1.5 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                    View Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No trainees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
