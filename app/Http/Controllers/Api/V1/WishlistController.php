<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WishlistResource;
use App\Models\Instrument;
use App\Traits\ApiResponse;

class WishlistController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $wishlistItems = auth()->user()->wishlistItems()
            ->with([
                'instrument.media',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
                'instrument.spec.instrumentFamily',
                'instrument.spec.topWood',
                'instrument.spec.backWood',
            ])
            ->latest()
            ->paginate(15);

        return $this->respondWithCollection(
            WishlistResource::collection($wishlistItems),
            'Wishlist items retrieved successfully.',
        );
    }

    public function store(Instrument $instrument)
    {
        abort_unless(
            $instrument->published_at !== null
                && $instrument->published_at->lte(now())
                && $instrument->stock_status !== Instrument::HIDDEN,
            404
        );

        auth()->user()->wishlistItems()->firstOrCreate([
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
        ]);

        return $this->successResponse('Instrument added to your wishlist.');
    }

    public function destroy(Instrument $instrument)
    {
        auth()->user()->wishlistItems()
            ->where('instrument_id', $instrument->id)
            ->firstOrFail()->delete();

        return $this->successResponse('Instrument removed from your wishlist.');
    }
}
