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

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-3">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
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

        {{-- START: PIN confirmation and justification upload --}}
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Attendance Confirmation</h2>

            @foreach ($modules as $module)
                <div class="bg-gray-50 rounded shadow p-4 mb-4">
                    <h3 class="font-semibold mb-2">{{ $module->name }}</h3>

                    <form method="POST" action="{{ route('trainee.attendance.confirm') }}">
                        @csrf
                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                        <input type="text" name="pin" placeholder="Enter PIN" class="border p-2 mr-2 w-40">
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
        {{-- END --}}
    </div>

</body>
</html>
