<?php

namespace App\Http\Controllers\Admin;

use App\Models\Instrument;
use App\Models\InstrumentSpec;
use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use App\Models\Builder;
use App\Models\Wood;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\StoreInstrumentRequest;
use App\Http\Requests\Admin\UpdateInstrumentRequest;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instruments = Instrument::with([
            'spec.builder',
            'spec.instrumentFamily',
            'spec.instrumentType',
            'media',
        ])
            ->latest()
            ->paginate(10);

        return view('admin.instruments.index', compact('instruments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.instruments.create', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstrumentRequest $request)
    {
        $instrument = null;
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, &$instrument) {
            $spec = InstrumentSpec::create([
                'instrument_family_id' => $validated['instrument_family_id'],
                'builder_id' => $validated['builder_id'],
                'instrument_type_id' => $validated['instrument_type_id'],
                'model' => $validated['model'] ?? null,
                'num_strings' => $validated['num_strings'] ?? null,
                'back_wood_id' => $validated['back_wood_id'] ?? null,
                'top_wood_id' => $validated['top_wood_id'] ?? null,
                'style' => $validated['style'] ?? null,
                'finish' => $validated['finish'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $instrument = Instrument::create([
                'instrument_spec_id' => $spec->id,
                'serial_number' => $validated['serial_number'],
                'sku' => $validated['sku'] ?? null,
                'price' => $validated['price'],
                'condition' => $validated['condition'],
                'stock_status' => $validated['stock_status'],
                'year_made' => isset($validated['year_made'])
                    ? sprintf('%d-01-01', $validated['year_made'])
                    : null,
                'quantity' => $validated['quantity'],
                'featured' => $request->boolean('featured'),
                'published_at' => $validated['published_at'] ?? null,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        });

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $instrument->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return redirect()
            ->route('admin.instruments.index')
            ->with('success', 'Instrument created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instrument $instrument)
    {
        $instrument->load('spec');

        return view('admin.instruments.edit', array_merge(
            $this->formData(),
            compact('instrument')
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstrumentRequest $request, Instrument $instrument)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $instrument) {
            $instrument->spec->update([
                'instrument_family_id' => $validated['instrument_family_id'],
                'builder_id' => $validated['builder_id'],
                'instrument_type_id' => $validated['instrument_type_id'],
                'model' => $validated['model'] ?? null,
                'num_strings' => $validated['num_strings'] ?? null,
                'back_wood_id' => $validated['back_wood_id'] ?? null,
                'top_wood_id' => $validated['top_wood_id'] ?? null,
                'style' => $validated['style'] ?? null,
                'finish' => $validated['finish'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $instrument->update([
                'serial_number' => $validated['serial_number'],
                'sku' => $validated['sku'] ?? null,
                'price' => $validated['price'],
                'condition' => $validated['condition'],
                'stock_status' => $validated['stock_status'],
                'year_made' => isset($validated['year_made'])
                    ? sprintf('%d-01-01', $validated['year_made'])
                    : null,
                'quantity' => $validated['quantity'],
                'featured' => $request->boolean('featured'),
                'published_at' => $validated['published_at'] ?? null,
                'updated_by' => $request->user()->id,
            ]);
        });

        if (! empty($validated['delete_media'])) {
            $instrument->media()
                ->whereIn('id', $validated['delete_media'])
                ->get()
                ->each
                ->delete();
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $instrument->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return redirect()
            ->route('admin.instruments.index')
            ->with('success', 'Instrument updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instrument $instrument)
    {
        $instrument->clearMediaCollection('gallery');
        $instrument->delete();

        return redirect()
            ->route('admin.instruments.index')
            ->with('success', 'Instrument deleted successfully.');
    }

    protected function formData(): array
    {
        return [
            'instrumentFamilies' => InstrumentFamily::orderBy('name')->get(),
            'builders' => Builder::orderBy('name')->get(),
            'instrumentTypes' => InstrumentType::with('instrumentFamily')->orderBy('name')->get(),
            'woods' => Wood::orderBy('name')->get(),
            'conditions' => Instrument::conditionTypes(),
            'stockStatuses' => Instrument::stockStatus(),
        ];
    }
}
