<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\InstrumentResource;
use App\Models\Instrument;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{
    public function index(Request $request)
    {
        $instruments = Instrument::query()
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
            ->ofSearch($request->query('q'))
            ->with([
                'spec.instrumentFamily',
                'spec.builder',
                'spec.instrumentType',
                'spec.backWood',
                'spec.topWood',
                'media',
            ])
            ->ofSort($request->string('sort')->toString())
            ->paginate(15)
            ->withQueryString();

        return $this->respondWithCollection(
            InstrumentResource::collection($instruments),
            'Customer instruments fetched successfully.',
        );
    }

    public function show(Instrument $instrument)
    {
        abort_unless(
            $instrument->published_at !== null
                && $instrument->published_at->lte(now())
                && $instrument->stock_status !== Instrument::HIDDEN,
            404
        );

        $instrument->load([
            'spec.instrumentFamily',
            'spec.builder',
            'spec.instrumentType',
            'spec.backWood',
            'spec.topWood',
            'media',
        ]);

        return $this->resourceResponse(
            new InstrumentResource($instrument),
            'Customer instrument fetched successfully.'
        );
    }
}
