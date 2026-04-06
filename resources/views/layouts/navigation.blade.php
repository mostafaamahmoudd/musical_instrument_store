@php
    $user = auth()->user();

    $links = [];

    if ($user?->isAdmin()) {
        $links = [
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Instruments', 'href' => route('admin.instruments.index'), 'active' => request()->routeIs('admin.instruments.*')],
            ['label' => 'Inquiries', 'href' => route('admin.inquiries.index'), 'active' => request()->routeIs('admin.inquiries.*')],
            ['label' => 'Reservations', 'href' => route('admin.reservations.index'), 'active' => request()->routeIs('admin.reservations.*')],
            ['label' => 'Builders', 'href' => route('admin.builders.index'), 'active' => request()->routeIs('admin.builders.*')],
            ['label' => 'Families', 'href' => route('admin.instrument-families.index'), 'active' => request()->routeIs('admin.instrument-families.*')],
            ['label' => 'Types', 'href' => route('admin.instrument-types.index'), 'active' => request()->routeIs('admin.instrument-types.*')],
            ['label' => 'Woods', 'href' => route('admin.woods.index'), 'active' => request()->routeIs('admin.woods.*')],
        ];
    } elseif ($user) {
        $links = [
            ['label' => 'Home', 'href' => route('home'), 'active' => request()->routeIs('home')],
            ['label' => 'Browse Inventory', 'href' => route('storefront.instruments.index'), 'active' => request()->routeIs('storefront.instruments.*')],
            ['label' => 'Wishlist', 'href' => route('storefront.wishlist.index'), 'active' => request()->routeIs('storefront.wishlist.*')],
            ['label' => 'Reservations', 'href' => route('storefront.reservations.index'), 'active' => request()->routeIs('storefront.reservations.*')],
            ['label' => 'Inquiries', 'href' => route('storefront.inquiries.index'), 'active' => request()->routeIs('storefront.inquiries.*')],
            ['label' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*')],
        ];
    }
@endphp

<div class="flex h-full flex-col">
    <div class="border-b border-slate-200 px-6 py-6 dark:border-slate-800">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Workspace</p>
        <h1 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Laravel') }}</h1>
        @auth
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                {{ $user->isAdmin() ? 'Admin panel' : 'Customer account' }}
            </p>
        @else
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Guest access
            </p>
        @endauth
    </div>

    @auth
        <nav class="flex-1 min-h-0 space-y-1 overflow-y-auto px-4 py-6" aria-label="Sidebar">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @class([
                        'flex items-center rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-slate-900 text-white shadow-sm dark:bg-slate-100 dark:text-slate-900' => $link['active'],
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' => ! $link['active'],
                    ])
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="shrink-0 border-t border-slate-200 bg-white/95 px-4 py-6 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
            <div class="rounded-xl bg-slate-50 px-4 py-4 dark:bg-slate-800">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    Log Out
                </button>
            </form>
        </div>
    @else
        <nav class="flex-1 min-h-0 space-y-1 overflow-y-auto px-4 py-6" aria-label="Guest navigation">
            <a
                href="{{ route('home') }}"
                @class([
                    'flex items-center rounded-xl px-4 py-3 text-sm font-medium transition',
                    'bg-slate-900 text-white shadow-sm dark:bg-slate-100 dark:text-slate-900' => request()->routeIs('home'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' => ! request()->routeIs('home'),
                ])
            >
                Browse Storefront
            </a>
        </nav>

        <div class="shrink-0 border-t border-slate-200 bg-white/95 px-4 py-6 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
            <a
                href="{{ route('login') }}"
                @class([
                    'flex items-center rounded-xl px-4 py-3 text-sm font-medium transition',
                    'bg-slate-900 text-white shadow-sm dark:bg-slate-100 dark:text-slate-900' => request()->routeIs('login'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' => ! request()->routeIs('login'),
                ])
            >
                Log In
            </a>

            @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    @class([
                        'mt-3 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-slate-900 text-white shadow-sm dark:bg-slate-100 dark:text-slate-900' => request()->routeIs('register'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' => ! request()->routeIs('register'),
                    ])
                >
                    Register
                </a>
            @endif
        </div>
    @endauth
</div>
