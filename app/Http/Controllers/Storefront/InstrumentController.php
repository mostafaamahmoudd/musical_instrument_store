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
        $families = InstrumentFamily::orderBy('name')
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
            ->take(8)
            ->get();

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
            ->ofPrice($request->input('lowPrice'), $request->input('highPrice'));

        match ($sort) {
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $instruments = $query->paginate(10)->withQueryString();

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
        $instrument->load([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'spec.backWood',
            'spec.topWood',
            'media',
        ]);

        abort_unless(
            $instrument->published_at !== null
            && $instrument->published_at->lte(now())
            && $instrument->stock_status !== Instrument::HIDDEN,
            404
        );

        $familyId = $instrument->spec?->instrument_family_id;

        $related = $familyId
            ? Instrument::with([
                'spec.builder',
                'spec.instrumentFamily',
                'spec.instrumentType',
                'media'
            ])
                ->ofVisible()
                ->whereKeyNot($instrument->id)
                ->ofFamily($familyId)
                ->latest()
                ->take(4)
                ->get()
            : Instrument::newCollection();

        return view('storefront.instruments.show', compact(
            'instrument',
            'related'
        ));
    }
}
