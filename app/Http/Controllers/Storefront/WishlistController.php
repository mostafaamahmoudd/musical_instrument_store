<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\WishlistItem;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = WishlistItem::where('user_id', auth()->id())
            ->with([
                'instrument.media',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
                'instrument.spec.instrumentFamily',
                'instrument.spec.topWood',
                'instrument.spec.backWood',
            ])
            ->latest()
            ->paginate(10);

        return view('storefront.wishlist.index', compact('wishlistItems'));
    }

    public function store(Instrument $instrument)
    {
        abort_unless($instrument->published_at && $instrument->stock_status !== 'hidden', 404);

        WishlistItem::firstOrCreate([
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
        ]);

        return redirect()->back()->with('success', 'Instrument added to your wishlist.');
    }

    public function destroy(Instrument $instrument)
    {
        WishlistItem::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->firstOrFail()->delete();

        return back()->with('success', 'Instrument removed from your wishlist.');
    }
}
