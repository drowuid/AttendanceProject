<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="UTF-8">
  <title>Welcome | Attendance System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind 3 with dark mode support -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    html {
      transition: background-color 0.3s ease, color 0.3s ease;
    }
  </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen flex flex-col">

  <!-- Dark Mode Toggle -->
  <div class="fixed top-4 right-4 z-50">
    <button id="darkModeToggle" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded-full shadow hover:scale-105 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path id="sunIcon" class="hidden" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L18.364 7.05M7.05 16.95l-1.414 1.414" />
        <path id="moonIcon" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
      </svg>
    </button>
  </div>

  <header class="bg-white dark:bg-gray-800 shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-blue-600 dark:text-white">Attendance System</h1>
    </div>
  </header>

  <section class="text-center py-24 bg-gradient-to-r from-indigo-600 to-blue-500 text-white" data-aos="fade-down">
    <div class="container mx-auto px-4">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">CESAE Attendance Management</h1>
      <p class="text-lg md:text-xl mb-6">Track, manage, and report attendance with ease.</p>
      <a href="{{ route('login') }}" class="inline-block bg-white text-indigo-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
        Login Now
      </a>
    </div>
  </section>

  <section class="py-20 bg-gray-100 dark:bg-gray-800">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-12 text-gray-800 dark:text-white" data-aos="fade-up">Features</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-6 text-center transition-card hover:shadow-lg" data-aos="fade-up" data-aos-delay="100">
          <svg class="mx-auto h-12 w-12 text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <h3 class="text-lg font-semibold mb-2">Trainee Tracking</h3>
          <p class="text-gray-600 dark:text-gray-300">Easily track individual trainee attendance and performance metrics.</p>
        </div>
        <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-6 text-center transition-card hover:shadow-lg" data-aos="fade-up" data-aos-delay="200">
          <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M12 8v4l3 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <h3 class="text-lg font-semibold mb-2">Module Overview</h3>
          <p class="text-gray-600 dark:text-gray-300">Visualize attendance and absences by training modules in real-time.</p>
        </div>
        <div class="bg-white dark:bg-gray-700 rounded-xl shadow p-6 text-center transition-card hover:shadow-lg" data-aos="fade-up" data-aos-delay="300">
          <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <h3 class="text-lg font-semibold mb-2">Absence Reports</h3>
          <p class="text-gray-600 dark:text-gray-300">Generate PDF & Excel reports for in-depth absence analysis and export.</p>
        </div>
      </div>
    </div>
  </section>

  <footer class="text-center text-sm text-gray-500 dark:text-gray-400 py-6 mt-auto">
    &copy; {{ date('Y') }} CESAE Digital. All rights reserved.
  </footer>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init();</script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleButton = document.getElementById('darkModeToggle');
      const html = document.documentElement;
      const sunIcon = document.getElementById('sunIcon');
      const moonIcon = document.getElementById('moonIcon');

      const setTheme = (theme) => {
        if (theme === 'dark') {
          html.classList.add('dark');
          sunIcon.classList.remove('hidden');
          moonIcon.classList.add('hidden');
        } else {
          html.classList.remove('dark');
          sunIcon.classList.add('hidden');
          moonIcon.classList.remove('hidden');
        }
        localStorage.setItem('theme', theme);
      };

      const storedTheme = localStorage.getItem('theme');
      if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        setTheme('dark');
      } else {
        setTheme('light');
      }

      toggleButton.addEventListener('click', () => {
        setTheme(html.classList.contains('dark') ? 'light' : 'dark');
      });
    });
  </script>
</body>
</html>
