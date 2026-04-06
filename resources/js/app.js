import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const themeToggles = document.querySelectorAll('[data-theme-toggle]');
const themeLabels = document.querySelectorAll('[data-theme-label]');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

const applyTheme = (theme) => {
    const isDark = theme === 'dark';
    document.documentElement.classList.toggle('dark', isDark);

    themeToggles.forEach((button) => {
        button.setAttribute('aria-pressed', String(isDark));
    });

    themeLabels.forEach((label) => {
        label.textContent = isDark ? 'Light mode' : 'Dark mode';
    });
};

const storedTheme = localStorage.getItem('theme');
applyTheme(storedTheme ?? (prefersDark.matches ? 'dark' : 'light'));

prefersDark.addEventListener('change', (event) => {
    if (!localStorage.getItem('theme')) {
        applyTheme(event.matches ? 'dark' : 'light');
    }
});

themeToggles.forEach((button) => {
    button.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        localStorage.setItem('theme', nextTheme);
        applyTheme(nextTheme);
    });
});
