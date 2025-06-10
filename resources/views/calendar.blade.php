<!DOCTYPE html>
<html>
<head>
    <title>Module Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Training Calendar</h1>
        @foreach ($modules as $module)
            <div class="mb-4">
                <p class="font-semibold">{{ $module->name }}</p>
                <p class="text-sm text-gray-600">{{ $module->start_date }} → {{ $module->end_date }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
