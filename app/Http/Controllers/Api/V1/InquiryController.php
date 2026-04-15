<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InquiryRequest;
use App\Http\Resources\Api\InquiryResource;
use App\Models\Inquiry;
use App\Models\Instrument;
use App\Traits\ApiResponse;

class InquiryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $inquiries = auth()->user()->inquiries()
            ->with([
                'instrument.media',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
                'instrument.spec.instrumentFamily',
            ])
            ->latest()
            ->paginate(15);

        return $this->respondWithCollection(
            InquiryResource::collection($inquiries),
            'Inquiries retrieved successfully.'
        );
    }

    public function show(Inquiry $inquiry)
    {
        abort_unless($inquiry->user_id === auth()->id(), 403);

        $inquiry->load([
            'instrument.media',
            'instrument.spec.builder',
            'instrument.spec.instrumentType',
            'instrument.spec.instrumentFamily',
        ]);

        return $this->respondWithResource(
            new InquiryResource($inquiry),
            'Inquiry retrieved successfully.'
        );
    }

    public function store(InquiryRequest $request, Instrument $instrument)
    {
        abort_unless(
            $instrument->published_at !== null
                && $instrument->published_at->lte(now())
                && $instrument->stock_status !== Instrument::HIDDEN,
            404
        );

        $validated = $request->validated() + [
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
        ];

        Inquiry::create($validated);

        return $this->respondWithSuccess('Your inquiry has been submitted successfully.');
    }
}
