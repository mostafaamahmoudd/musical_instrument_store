<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{
    public function home()
    {
        $famillies = InstrumentFamily::orderBy('name')
            ->get();

        $instruments = Instrument::with([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'media',
        ])
            ->ofVisible()
            ->ofFeatured()
            ->latest()
            ->paginate(10);

        return view('storefront.home', compact('families', 'instruments'));
    }

    public function index(Request $request)
    {
        $sort = $request->string('sort')->toString();

        $query = Instrument::with([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'media',
        ])
            ->ofVisible()
            ->ofFamily($request->integer('family'))
            ->ofBuilder($request->integer('builder'))
            ->ofCondition($request->string('condition')->toString())
            ->ofPrice($request->lowPrice, $request->highPrice)
            ->get();

        match ($sort) {
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $instruments = $query->paginate(10);

        $families = InstrumentFamily::orderBy('name')->get();
        $builders = Builder::orderBy('name')->get();
        $conditions = Instrument::conditionTypes();

        $currentFamily = $request->integer('family')
            ? InstrumentFamily::find($request->integer('family'))
            : null;

        return view('storefront.instruments.index', compact(
            'instruments',
            'families',
            'builders',
            'conditions',
            'currentFamily'
        ));
    }

    public function show(Instrument $instrument)
    {
        abort_unless(
            Instrument::query()
                ->whereKey($instrument->id)
                ->ofVisible()
                ->exists(),
            404
        );

        $instrument->load([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'spec.backWood',
            'spec.topWood',
            'media',
        ]);

        $related = Instrument::with([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'media'
        ])
            ->ofVisible()
            ->whereKeyNot($instrument->id)
            ->whereHas('spec', function ($query) use ($instrument) {
                $query->where('instrument_family_id', $instrument->spec?->instrument_family_id);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('storefront.instruments.show', compact(
            'instrument',
            'related'
        ));
    }
}
