<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use App\Models\Wood;
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
        $query = Instrument::with([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'spec.backWood',
            'spec.topWood',
            'media',
        ])
            ->ofVisible()
            ->ofFamily($request->integer('family'))
            ->ofType($request->integer('type'))
            ->ofBuilder($request->integer('builder'))
            ->ofTopWood($request->integer('top_wood'))
            ->ofBackWood($request->integer('back_wood'))
            ->ofCondition($request->string('condition')->toString())
            ->ofStock($request->string('stock')->toString())
            ->ofPrice(
                $request->input('price_min', $request->input('lowPrice')),
                $request->input('price_max', $request->input('highPrice'))
            )
            ->ofSort($request->string('sort')->toString())
            ->ofSearch($request->query('q'));

        $instruments = $query->paginate(10)->withQueryString();

        $families = InstrumentFamily::orderBy('name')->get();
        $builders = Builder::orderBy('name')->get();
        $types = InstrumentType::orderBy('name')->get();
        $woods = Wood::orderBy('name')->get();
        $conditions = Instrument::conditionTypes();
        $stockStatuses = Instrument::stockStatus();

        $currentFamily = $request->integer('family')
            ? InstrumentFamily::find($request->integer('family'))
            : null;

        return view('storefront.instruments.index', compact(
            'instruments',
            'families',
            'builders',
            'types',
            'woods',
            'conditions',
            'stockStatuses',
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
