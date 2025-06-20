// public/js/theme.js
(function () {
    const html = document.documentElement;
    const toggle = document.getElementById('darkModeToggle');

    function applyTheme(theme) {
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        localStorage.setItem('theme', theme);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme) {
            applyTheme(storedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            applyTheme('dark');
        }

        if (toggle) {
            toggle.addEventListener('click', () => {
                const isDark = html.classList.contains('dark');
                applyTheme(isDark ? 'light' : 'dark');
            });
        }
    });
})();
