<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- If you are using Vite --}}
     <script src="{{ asset('js/theme.js') }}" defer></script>
    {{-- Or, if you are using Laravel Mix: --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
</head>
<body>
    <div id="app">
        <nav>
            <p>Admin Navigation Placeholder</p>
        </nav>

        <main>
            @yield('content') {{-- This is where your dashboard.blade.php content will be injected --}}
        </main>

        <footer>
            <p>Admin Footer Placeholder</p>
        </footer>
    </div>

    @yield('scripts') {{-- This is crucial for your Chart.js script to be injected --}}
</body>
</html>
