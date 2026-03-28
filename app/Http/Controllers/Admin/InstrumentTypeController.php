<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstrumentTypeRequest;
use App\Http\Requests\Admin\UpdateInstrumentTypeRequest;
use App\Models\InstrumentType;

class InstrumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instrumentTypes = InstrumentType::latest()->paginate(10);

        return view('admin.instrument-types.index', compact('instrumentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstrumentTypeRequest $request)
    {
        InstrumentType::create($request->validated());

        return redirect()->route('admin.instrument-types.index')
            ->with('success', 'Instrument Type created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.instrument-types.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstrumentType $instrumentType)
    {
        return view('admin.instrument-types.edit', compact('instrumentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstrumentTypeRequest $request, InstrumentType $instrumentType)
    {
        $instrumentType->update($request->validated());

        return redirect()->route('admin.instrument-types.index')
            ->with('success', 'Instrument Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstrumentType $instrumentType)
    {
        $instrumentType->delete();

        return redirect()->route('admin.instrument-types.index')
            ->with('success', 'Instrument Type deleted successfully.');
    }
}
