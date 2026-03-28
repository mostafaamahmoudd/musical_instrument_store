<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstrumentFamilyRequest;
use App\Http\Requests\Admin\UpdateInstrumentFamilyRequest;
use App\Models\InstrumentFamily;

class InstrumentFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instrumentFamilies = InstrumentFamily::latest()->paginate(10);

        return view('admin.instrument-families.index', compact('instrumentFamilies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstrumentFamilyRequest $request)
    {
        InstrumentFamily::create($request->validated());

        return redirect()->route('admin.instrument-families.index')
            ->with('success', 'Instrument family created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.instrument-families.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstrumentFamily $instrumentFamily)
    {
        return view('admin.instrument-families.edit', compact('instrumentFamily'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstrumentFamilyRequest $request, InstrumentFamily $instrumentFamily)
    {
        $instrumentFamily->update($request->validated());

        return redirect()->route('admin.instrument-families.index')
            ->with('success', 'Instrument family updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstrumentFamily $instrumentFamily)
    {
        $instrumentFamily->delete();

        return redirect()->route('admin.instrument-families.index')
            ->with('success', 'Instrument family deleted successfully');
    }
}
