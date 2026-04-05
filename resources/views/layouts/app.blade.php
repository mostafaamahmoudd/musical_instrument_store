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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100 lg:flex">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 max-w-[85vw] -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 ease-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : ''"
        >
            @include('layouts.navigation')
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-0">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-600 lg:hidden"
                            @click="sidebarOpen = true"
                            aria-label="Open sidebar"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4A1 1 0 013 5Zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1Zm1 4a1 1 0 100 2h12a1 1 0 100-2H4Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        @isset($header)
                            <div class="min-w-0">
                                {{ $header }}
                            </div>
                        @else
                            <div>
                                <h1 class="text-lg font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</h1>
                            </div>
                        @endisset
                    </div>

                    @auth
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}
                            </p>
                        </div>
                    @else
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-medium text-slate-900">Guest</p>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Public storefront</p>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
