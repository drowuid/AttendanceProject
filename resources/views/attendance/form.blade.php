<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Logging</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">

<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Log Entry / Exit</h1>

    @if(session('success'))
        <div class="bg-green-100 p-2 rounded mb-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('attendance.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="trainee_id">Trainee ID</label>
            <input type="number" name="trainee_id" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="module_id">Module</label>
            <select name="module_id" class="w-full border p-2 rounded" required>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="type">Type</label>
            <select name="type" class="w-full border p-2 rounded" required>
                <option value="entry">Entry</option>
                <option value="exit">Exit</option>
            </select>
        </div>

        <div>
            <label for="reason">Reason (if late)</label>
            <input type="text" name="reason" class="w-full border p-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
    </form>
</div>

</body>
</html>
