<div class="pt-2 pb-3 space-y-1">
    @auth
        @if (Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Admin Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.builders.index')" :active="request()->routeIs('admin.builders.*')">
                {{ __('Builders') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.instrument-families.index')" :active="request()->routeIs('admin.instrument-families.*')">
                {{ __('Families') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.instrument-types.index')" :active="request()->routeIs('admin.instrument-types.*')">
                {{ __('Types') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.woods.index')" :active="request()->routeIs('admin.woods.*')">
                {{ __('Woods') }}
            </x-responsive-nav-link>
        @else
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
        </form>
    @endauth
</div>
