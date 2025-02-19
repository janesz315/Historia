<?php

namespace App\Http\Controllers;

use App\Models\UserTest;
use App\Http\Requests\StoreUserTestRequest;
use App\Http\Requests\UpdateUserTestRequest;

class UserTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = UserTest::all();
        // $rows = Diak::orderBy('nev', 'asc')->get();
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserTestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserTest $userTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserTestRequest $request, UserTest $userTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserTest $userTest)
    {
        //
    }
}
