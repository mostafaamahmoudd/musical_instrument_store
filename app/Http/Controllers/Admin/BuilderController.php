<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBuilderRequest;
use App\Http\Requests\Admin\UpdateBuilderRequest;
use App\Models\Builder;

class BuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $builders = Builder::latest()->paginate(10);

        return view('admin.builders.index', compact('builders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuilderRequest $request)
    {
        Builder::create([
            $request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.builders.index')
            ->with('success', 'Builder created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.builders.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Builder $builder)
    {
        return view('admin.builders.edit', compact('builder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuilderRequest $request, Builder $builder)
    {
        $builder->update([
            $request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.builders.index')
            ->with('success', 'Builder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Builder $builder)
    {
        $builder->delete();

        return redirect()->route('admin.builders.index')
            ->with('success', 'Builder deleted successfully.');
    }
}
