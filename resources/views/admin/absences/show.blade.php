@extends('layouts.admin')

@section('content')
<h2 class="text-xl font-bold mb-4">Absence Details</h2>

<ul class="bg-white shadow p-4 rounded">
    <li><strong>User:</strong> {{ $absence->user->name }}</li>
    <li><strong>Module:</strong> {{ $absence->module->name }}</li>
    <li><strong>Date:</strong> {{ $absence->date }}</li>
    <li><strong>Reason:</strong> {{ $absence->reason }}</li>
</ul>

<a href="{{ route('admin.absences.index') }}" class="mt-4 inline-block text-blue-600">← Back to List</a>
@endsection
