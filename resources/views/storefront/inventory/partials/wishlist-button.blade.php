@auth
    @php
        $isWishlisted = auth()->user()->wishlistItems()->where('instrument_id', $instrument->id)->exists();
    @endphp

    @if ($isWishlisted)
        <form action="{{ route('storefront.wishlist.destroy', $instrument) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center rounded-md border border-rose-200 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">
                Remove
            </button>
        </form>
    @else
        <form action="{{ route('storefront.wishlist.store', $instrument) }}" method="POST">
            @csrf
            <button type="submit"
                class="inline-flex items-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Save
            </button>
        </form>
    @endif
@else
    <a href="{{ route('login') }}"
        class="inline-flex items-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
        Login to save
    </a>
@endauth
