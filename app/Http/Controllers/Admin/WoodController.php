<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWoodRequest;
use App\Http\Requests\Admin\UpdateWoodRequest;
use App\Models\Wood;

class WoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wood = Wood::latest()->paginate(15);

        return view('admin.woods.index', compact('wood'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWoodRequest $request)
    {
        Wood::create($request->validated());

        return redirect()->route('admin.woods.index')
            ->with('success', 'Wood created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.woods.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wood $wood)
    {
        return view('admin.woods.edit', compact('wood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWoodRequest $request, Wood $wood)
    {
        $wood->update($request->validated());

        return redirect()->route('admin.woods.index')
            ->with('success', 'Wood updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wood $wood)
    {
        $wood->delete();

        return redirect()->route('admin.woods.index')
            ->with('success', 'Wood deleted successfully');
    }
}
