@extends('layouts.admin')

@section('content')
<h2 class="text-xl font-bold mb-4">Edit Absence</h2>

<form method="POST" action="{{ route('admin.absences.update', $absence) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label>User</label>
        <select name="user_id" class="w-full p-2 border rounded">
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected($absence->user_id == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Module</label>
        <select name="module_id" class="w-full p-2 border rounded">
            @foreach($modules as $module)
                <option value="{{ $module->id }}" @selected($absence->module_id == $module->id)>
                    {{ $module->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Date</label>
        <input type="date" name="date" value="{{ $absence->date }}" class="w-full p-2 border rounded" required>
    </div>

    <div>
        <label>Reason</label>
        <textarea name="reason" class="w-full p-2 border rounded">{{ $absence->reason }}</textarea>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
</form>
@endsection
