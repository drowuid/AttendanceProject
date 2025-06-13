@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-6">Trainer Module Reports</h1>

    @if($modules->isEmpty())
        <p class="text-gray-500">No modules assigned to you.</p>
    @else
        <p class="mb-4 text-gray-600">Select a module to view trainee attendance details:</p>

        <ul class="space-y-3">
            @foreach($modules as $module)
                <li>
                    <a href="{{ route('trainer.moduleReports.show', $module->id) }}"
                       class="block p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-blue-800">{{ $module->name }}</span>
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($module->start_date)->format('d M Y') }}
                                –
                                {{ \Carbon\Carbon::parse($module->end_date)->format('d M Y') }}
                            </span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
