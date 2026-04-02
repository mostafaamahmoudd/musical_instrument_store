<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\InquiryRequest;
use App\Models\Inquiry;
use App\Models\Instrument;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::where('user_id', auth()->id())
            ->with([
                'instrument.media',
                'instrument.spec.builder',
                'instrument.spec.instrumentType',
                'instrument.spec.instrumentFamily',
            ])
            ->latest()
            ->paginate(10);

        return view('storefront.inquiries.index', compact('inquiries'));
    }

    public function store(InquiryRequest $request, Instrument $instrument)
    {
        abort_unless($instrument->published_at && $instrument->stock_status !== 'hidden', 404);

        $validated = $request->validated() + [
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
        ];

        Inquiry::create($validated);

        return redirect()
            ->route('storefront.instruments.show', $instrument)
            ->with('success', 'Your inquiry has been submitted successfully.');
    }

    public function create(Instrument $instrument)
    {
        abort_unless($instrument->published_at && $instrument->stock_status !== 'hidden', 404);

        return view('storefront.inquiries.create', [
            'instrument' => $instrument->load([
                'media',
                'spec.builder',
                'spec.instrumentType',
                'spec.instrumentFamily',
            ]),
            'user' => auth()->user(),
        ]);
    }

    public function show(Inquiry $inquiry)
    {
        if (! auth()->user()?->isAdmin() && $inquiry->user_id !== auth()->id()) {
            abort(403);
        }

        $inquiry->load([
            'instrument.media',
            'instrument.spec.builder',
            'instrument.spec.instrumentType',
            'instrument.spec.instrumentFamily',
        ]);

        return view('storefront.inquiries.show', compact('inquiry'));
    }
}
