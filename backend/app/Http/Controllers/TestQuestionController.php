<?php

namespace App\Http\Controllers;

use App\Models\TestQuestion;
use App\Http\Requests\StoreTestQuestionRequest;
use App\Http\Requests\UpdateTestQuestionRequest;

class TestQuestionController extends Controller
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
    public function store(StoreTestQuestionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TestQuestion $testQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestQuestionRequest $request, TestQuestion $testQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestQuestion $testQuestion)
    {
        //
    }
}
