<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\ReservationRequest;
use App\Models\Instrument;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $reservations = Reservation::query()
            ->where('user_id', auth()->id())
            ->with([
                'user',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
            ])
            ->ofStatus($status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('storefront.reservations.index', [
            'reservations' => $reservations,
            'statuses' => Reservation::statuses(),
            'selectedStatus' => $status,
        ]);
    }

    public function create(Request $request, Instrument $instrument)
    {
        ensureReservable($instrument);

        $instrument->load([
            'media',
            'spec.builder',
            'spec.instrumentType',
            'spec.instrumentFamily',
        ]);

        return view('storefront.reservations.create', [
            'instrument' => $instrument,
            'user' => auth()->user(),
        ]);
    }

    public function store(ReservationRequest $request, Instrument $instrument)
    {
        ensureReservable($instrument);

        $existing = Reservation::query()
            ->where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->whereIn('status', [
                Reservation::PENDING,
                Reservation::APPROVED,
            ])
            ->exists();

        if ($existing) {
            return redirect()
                ->route('storefront.instruments.show', $instrument)
                ->with('error', 'You already have an active reservation request for this instrument.');
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => $request->string('notes')->toString(),
        ]);

        return redirect()
            ->route('storefront.instruments.show', $instrument)
            ->with('success', 'Your reservation request has been submitted.');
    }
}
