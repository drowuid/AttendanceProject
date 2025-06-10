<!DOCTYPE html>
<html>
<head>
    <title>Course Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-3xl font-bold mb-6">Course Module Calendar</h1>

        @if($modules->count())
            <div class="grid grid-cols-1 gap-4">
                @foreach($modules as $module)
                    <div class="border rounded p-4 shadow-sm bg-blue-50">
                        <h2 class="text-xl font-semibold text-blue-800">{{ $module->name }}</h2>
                        <p class="text-gray-700">Start: {{ \Carbon\Carbon::parse($module->start_date)->format('d/m/Y') }}</p>
                        <p class="text-gray-700">End: {{ \Carbon\Carbon::parse($module->end_date)->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">Total Days: {{ \Carbon\Carbon::parse($module->start_date)->diffInDays(\Carbon\Carbon::parse($module->end_date)) + 1 }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>No modules available. Please add modules to see them here.</p>
        @endif
    </div>
</body>
</html>
