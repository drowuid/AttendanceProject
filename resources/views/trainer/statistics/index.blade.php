@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Absence Statistics by Module</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($statistics as $stat)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-xl font-semibold text-indigo-700 mb-3">{{ $stat['module'] }}</h2>
            <div class="space-y-2 text-base">
                <p>
                    <span class="font-medium text-gray-700">Total Absences:</span>
                    <span class="text-gray-900">{{ $stat['total'] }}</span>
                </p>
                <p>
                    <span class="font-medium text-green-700">Justified:</span>
                    <span class="text-green-700">{{ $stat['justified'] }}</span>
                </p>
                <p>
                    <span class="font-medium text-red-700">Unjustified:</span>
                    <span class="text-red-700">{{ $stat['unjustified'] }}</span>
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-400 text-lg py-12">
            No statistics available.
        </div>
        @endforelse
    </div>
</div>
@endsection
