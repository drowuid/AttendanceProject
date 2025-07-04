@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-xl font-bold mb-4">Attendance Confirmation</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-3">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach ($modules as $module)
        <div class="bg-white dark:bg-gray-800 rounded shadow p-4 mb-4">
            <h2 class="font-semibold mb-2">{{ $module->name }}</h2>
            <form method="POST" action="{{ route('trainee.attendance.confirm') }}">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <input type="text" name="pin" placeholder="Enter PIN" class="border p-2 mr-2">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Confirm Presence</button>
            </form>
            <form method="POST" action="{{ route('trainee.attendance.justify') }}" enctype="multipart/form-data" class="mt-2">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">
                <input type="file" name="justification_file" class="border p-2 mr-2">
                <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded">Upload Justification</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
