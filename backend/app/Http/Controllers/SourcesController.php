<?php

namespace App\Http\Controllers;

use App\Models\Sources;
use App\Http\Requests\StoreSourcesRequest;
use App\Http\Requests\UpdateSourcesRequest;

class SourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSourcesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sources $sources)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSourcesRequest $request, Sources $sources)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sources $sources)
    {
        //
    }
}
