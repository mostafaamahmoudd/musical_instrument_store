<div class="flex flex-wrap items-center justify-center gap-4 py-2">
    @auth
        @if (Auth::user()->isAdmin())
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Admin Dashboard') }}
            </x-nav-link>

            <x-nav-link :href="route('admin.instruments.index')" :active="request()->routeIs('admin.instruments.*')">
                {{ __('Instruments') }}
            </x-nav-link>

            <x-nav-link :href="route('admin.builders.index')" :active="request()->routeIs('admin.builders.*')">
                {{ __('Builders') }}
            </x-nav-link>

            <x-nav-link :href="route('admin.instrument-families.index')" :active="request()->routeIs('admin.instrument-families.*')">
                {{ __('Families') }}
            </x-nav-link>

            <x-nav-link :href="route('admin.instrument-types.index')" :active="request()->routeIs('admin.instrument-types.*')">
                {{ __('Types') }}
            </x-nav-link>

            <x-nav-link :href="route('admin.woods.index')" :active="request()->routeIs('admin.woods.*')">
                {{ __('Woods') }}
            </x-nav-link>
        @else
            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-nav-link>

            <x-nav-link :href="route('storefront.instruments.index')" :active="request()->routeIs('storefront.instruments.*')">
                {{ __('Search') }}
            </x-nav-link>

            <x-nav-link :href="route('storefront.wishlist.index')" :active="request()->routeIs('storefront.wishlist.*')">
                {{ __('Wishlist') }}
            </x-nav-link>

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>

            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                {{ __('Profile') }}
            </x-nav-link>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf

            <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-nav-link>
        </form>
    @endauth

    @guest
        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
            {{ __('Log In') }}
        </x-nav-link>

        @if (Route::has('register'))
            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                {{ __('Register') }}
            </x-nav-link>
        @endif
    @endguest
</div>
