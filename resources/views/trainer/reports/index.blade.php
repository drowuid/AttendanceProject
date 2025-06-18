@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Absence Reports</h1>

    <form method="GET" class="flex flex-wrap gap-4 mb-6">
        <select name="module_id" class="border p-2 rounded">
            <option value="">All Modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>

        <a href="{{ route('trainer.export.absences') }}" class="ml-auto bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Export to Excel
        </a>
    </form>

    <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Trainee</th>
                    <th class="py-2">Module</th>
                    <th class="py-2">Date</th>
                    <th class="py-2">Reason</th>
                    <th class="py-2">Justified</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absences as $absence)
                <tr class="border-b">
                    <td class="py-2">{{ $absence->trainee->name }}</td>
                    <td class="py-2">{{ $absence->module->name }}</td>
                    <td class="py-2">{{ $absence->date }}</td>
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
                    <td colspan="5" class="py-4 text-center text-gray-500">No absences found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $absences->withQueryString()->links() }}
    </div>
</div>
@endsection
