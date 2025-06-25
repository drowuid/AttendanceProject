@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-md">
    <h1 class="text-2xl font-bold mb-6">Edit Role for {{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
        @csrf
        @method('PUT')

        <label for="role" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Select Role</label>
        <select id="role" name="role" required
                class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-white">
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
        @error('role')
            <p class="text-red-600 mt-2">{{ $message }}</p>
        @enderror

        <div class="mt-6">
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold">
                Update Role
            </button>
            <a href="{{ route('admin.users.index') }}"
                class="ml-4 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white font-semibold">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
