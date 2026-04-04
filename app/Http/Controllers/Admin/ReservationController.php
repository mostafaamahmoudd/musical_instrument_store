<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationRequest;
use App\Models\Instrument;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $reservations = Reservation::query()
            ->with([
                'user',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
            ])->ofStatus($status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reservations.index', [
            'reservations' => $reservations,
            'statusFilter' => $status,
            'statuses' => Reservation::statuses(),
        ]);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'user',
            'instrument.media',
            'instrument.spec.builder',
            'instrument.spec.instrumentType',
            'instrument.spec.instrumentFamily',
        ]);

        return view('admin.reservations.show', [
            'reservation' => $reservation,
            'statuses' => Reservation::statuses(),
        ]);
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();
        $instrument = $reservation->instrument;

        if (
            $validated['status'] === Reservation::APPROVED
            && $reservation->status !== Reservation::APPROVED
            && Reservation::query()
                ->where('instrument_id', $instrument->id)
                ->where('id', '!=', $reservation->id)
                ->where('status', Reservation::APPROVED)
                ->exists()
        ) {
            return back()
                ->withErrors([
                    'status' => 'This instrument already has an approved reservation.',
                ])
                ->withInput();
        }

        if ($validated['status'] !== Reservation::APPROVED) {
            $validated['reserved_until'] = null;
        }

        $reservation->update($validated);

        if ($validated['status'] === Reservation::APPROVED) {
            $instrument->update([
                'stock_status' => Instrument::RESERVED,
            ]);
        }

        if (in_array($validated['status'], [
            Reservation::PENDING,
            Reservation::REJECTED,
            Reservation::EXPIRED,
            Reservation::CANCELLED,
        ])) {
            $hasOtherApprovedReservation = Reservation::query()
                ->where('instrument_id', $instrument->id)
                ->where('id', '!=', $reservation->id)
                ->where('status', Reservation::APPROVED)
                ->exists();

            if (!$hasOtherApprovedReservation && $instrument->stock_status === Instrument::RESERVED) {
                $instrument->update([
                    'stock_status' => Instrument::AVAILABLE,
                ]);
            }
        }

        return redirect()->route('admin.reservations.show', $reservation)
            ->with('success', 'Reservation updated successfully.');
    }
}
