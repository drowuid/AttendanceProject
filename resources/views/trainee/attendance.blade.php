<!DOCTYPE html>
<html>
<head>
    <title>Trainee Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">Log Your Attendance</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('trainee.attendance.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Select Action</label>
                <select name="status" class="w-full border rounded p-2" required>
                    <option value="present">Entry</option>
                    <option value="exit">Exit</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Reason (Optional, for lateness or exit)</label>
                <input type="text" name="reason" class="w-full border rounded p-2" />
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
</body>
</html>
