@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-900 dark:text-white flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4H5v4a2 2 0 002 2h2a2 2 0 002-2zM19 17v-4h-4v4a2 2 0 002 2h2a2 2 0 002-2zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2" />
        </svg>
        Manage All Absences
    </h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
        <select name="user_id" class="border p-2 rounded w-48">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <select name="module_id" class="border p-2 rounded w-48">
            <option value="">All Modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}" @selected(request('module_id') == $module->id)>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>

        <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}"
               class="border p-2 rounded w-64" />

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Filter
        </button>

        <a href="{{ route('admin.absences.export', request()->query()) }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
           Export PDF
        </a>

        <a href="{{ route('admin.absences.calendar') }}"
           class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
           📅 Calendar View
        </a>
    </form>

    <div class="overflow-x-auto bg-white rounded-2xl shadow">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-3 text-left">Trainee</th>
                    <th class="border p-3 text-left">Module</th>
                    <th class="border p-3 text-left">Date</th>
                    <th class="border p-3 text-left">Excused?</th>
                    <th class="border p-3 text-left">Reason</th>
                    <th class="border p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $absence->user->name }}</td>
                        <td class="border p-3">{{ $absence->module->name }}</td>
                        <td class="border p-3">{{ $absence->date }}</td>
                        <td class="border p-3">{{ $absence->is_excused ? 'Yes' : 'No' }}</td>
                        <td class="border p-3">{{ $absence->reason ?? '-' }}</td>
                        <td class="border p-3">
                            <div class="flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.absences.update', $absence->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex gap-2">
                                        <select name="is_excused" class="border rounded p-1">
                                            <option value="1" {{ $absence->is_excused ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ !$absence->is_excused ? 'selected' : '' }}>No</option>
                                        </select>
                                        <input type="text" name="reason" placeholder="Reason..." value="{{ $absence->reason }}"
                                               class="border p-1 rounded w-32">
                                        <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                            Update
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('admin.absences.destroy', $absence->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border p-4 text-center text-gray-500">
                            No absences found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $absences->links() }}
    </div>
</div>
@endsection
