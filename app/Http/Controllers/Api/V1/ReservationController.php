<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReservationRequest;
use App\Http\Resources\Api\ReservationResource;
use App\Models\Instrument;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = auth()->user()->reservations()
            ->with([
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
            ])
            ->ofStatus($request->string('status')->toString())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return $this->respondWithCollection(
            ReservationResource::collection($reservations),
            'Reservations retrieved successfully.',
        );
    }

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'instrument.spec.builder',
            'instrument.spec.instrumentType',
        ]);

        return $this->respondWithResource(
            new ReservationResource($reservation),
            'Reservation retrieved successfully.',
        );
    }

    public function store(ReservationRequest $request, Instrument $instrument)
    {
        ensureReservable($instrument);

        $existing = auth()->user()->reservations()
            ->where('instrument_id', $instrument->id)
            ->whereIn('status', [
                Reservation::PENDING,
                Reservation::APPROVED,
            ])
            ->exists();

        if ($existing) {
            return $this->respondWithError('You already have an active reservation request for this instrument.');
        }

        $reservation = auth()->user()->reservations()->create([
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => $request->string('notes')->toString(),
        ]);

        return $this->respondWithResource(
            new ReservationResource($reservation),
            'Reservation created successfully.'
        );
    }
}
