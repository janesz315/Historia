<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Http\Requests\StoreAnswersRequest;
use App\Http\Requests\UpdateAnswersRequest;

class AnswersController extends Controller
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
    public function store(StoreAnswersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Answers $answers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswersRequest $request, Answers $answers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answers $answers)
    {
        //
    }
}
