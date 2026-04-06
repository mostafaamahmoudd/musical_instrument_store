<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        (() => {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100 dark:bg-slate-950 lg:flex">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 max-w-[85vw] -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 ease-out dark:border-slate-800 dark:bg-slate-900 lg:fixed lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : ''"
        >
            @include('layouts.navigation')
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-600 dark:border-slate-700 dark:text-slate-200 lg:hidden"
                            @click="sidebarOpen = true"
                            aria-label="Open sidebar"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4A1 1 0 013 5Zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1Zm1 4a1 1 0 100 2h12a1 1 0 100-2H4Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Guest Access</p>
                            <h1 class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Laravel') }}</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            data-theme-toggle
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                            aria-pressed="false"
                        >
                            <span data-theme-label>Dark mode</span>
                        </button>

                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">Authentication</p>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Login or register</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex flex-1 items-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-md rounded-2xl border border-slate-200 bg-white px-6 py-8 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:px-8">
                    <div class="mb-6 flex justify-center">
                        <a href="/">
                            <x-application-logo class="h-16 w-16 fill-current text-slate-500 dark:text-slate-400" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
