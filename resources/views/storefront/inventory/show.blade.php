@include('storefront.inventory.partials.wishlist-button', ['instrument' => $instrument])


@auth
    @if ($instrument->stock_status === 'available')
        <a
            href="{{ route('storefront.reservations.create', $instrument) }}"
            class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
        >
            Request reservation
        </a>
    @endif
@endauth
