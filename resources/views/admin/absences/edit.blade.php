@extends('layouts.admin')

@section('content')
<div class="container mx-auto max-w-2xl py-10 px-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Edit Absence</h1>

    <form method="POST" action="{{ route('admin.absences.update', $absence->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="user_id" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">User</label>
            <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($absence->user_id == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="module_id" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Module</label>
            <select id="module_id" name="module_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" @selected($absence->module_id == $module->id)>
                        {{ $module->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="date" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Date</label>
            <input type="date" id="date" name="date" value="{{ $absence->date }}"
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div class="mb-4">
            <label for="reason" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Reason</label>
            <input type="text" id="reason" name="reason" value="{{ $absence->reason }}"
                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-6">
            <label for="justified" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Justified</label>
            <select id="justified" name="justified"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="1" {{ $absence->justified ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$absence->justified ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition font-semibold">
                Update Absence
            </button>
        </div>
    </form>
</div>
@endsection
