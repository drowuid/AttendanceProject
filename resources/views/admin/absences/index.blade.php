@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">All Absences</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <select name="user_id" class="border p-2 rounded">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <select name="module_id" class="border p-2 rounded">
            <option value="">All Modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}" @selected(request('module_id') == $module->id)>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>

        <input type="text" name="search" placeholder="Search by user name" value="{{ request('search') }}" class="border p-2 rounded" />

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>

        <a href="{{ route('admin.absences.export', request()->query()) }}"
   class="bg-green-600 text-white px-4 py-2 rounded">
   Export PDF
</a>
    </form>

    <table class="w-full table-auto border-collapse shadow-sm bg-white rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Trainee</th>
                <th class="border p-2">Module</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Excused?</th>
                <th class="border p-2">Reason</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $absence)
                <tr>
                    <td class="border p-2">{{ $absence->user->name }}</td>
                    <td class="border p-2">{{ $absence->module->name }}</td>
                    <td class="border p-2">{{ $absence->date }}</td>
                    <td class="border p-2">{{ $absence->is_excused ? 'Yes' : 'No' }}</td>
                    <td class="border p-2">{{ $absence->reason ?? '-' }}</td>
                    <td class="border p-2">
                        <div class="flex flex-wrap gap-2 items-center">
                            <form method="POST" action="{{ route('admin.absences.update', $absence->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="flex gap-2 items-center">
                                    <select name="is_excused" class="border rounded p-1">
                                        <option value="1" {{ $absence->is_excused ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$absence->is_excused ? 'selected' : '' }}>No</option>
                                    </select>
                                    <input type="text" name="reason" placeholder="Reason..." value="{{ $absence->reason }}" class="border p-1 rounded w-32">
                                    <button class="bg-blue-500 text-white px-2 py-1 rounded">Update</button>
                                </div>
                            </form>

                            <form action="{{ route('admin.absences.destroy', $absence->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border p-4 text-center text-gray-500">No absences found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $absences->links() }}
    </div>
</div>
@endsection
