@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-5xl py-10 px-4">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-100">User Role Management</h1>
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-900 transition font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
            </svg>
            Back to Dashboard
        </a>
    </div>
    
    @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex gap-3 items-center">
    <label for="role" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Role:</label>
    <select name="role" id="role"
        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm"
        onchange="this.form.submit()">
        <option value="">All</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="trainer" {{ request('role') === 'trainer' ? 'selected' : '' }}>Trainer</option>
        <option value="trainee" {{ request('role') === 'trainee' ? 'selected' : '' }}>Trainee</option>
    </select>
</form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize text-gray-700 dark:text-gray-300">{{ $user->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.users.editRole', $user) }}"
                               class="inline-block px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs font-medium transition">
                                Edit Role
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
