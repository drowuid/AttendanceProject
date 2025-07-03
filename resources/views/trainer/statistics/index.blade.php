@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Absence Statistics by Module</h1>
            <a href="{{ route('trainer.dashboard') }}"
                class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-900 transition font-semibold ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
        </div>

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
