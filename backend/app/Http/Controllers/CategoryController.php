<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Category::all();
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
    public function store(StoreCategoryRequest $request)
    {
        $rows = Category::create(attributes: $request->all());
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $rows = Category::find($id);
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $row = Category::find($id);
        if ($row) {

            try {
                $row->update($request->all());
                $data = [
                    'row' => $row
                ];
            } catch (\Illuminate\Database\QueryException $e) {
                $data = [
                    'message' => 'source incorrect',
                    'category' => $request['category'],
                    'level' => $request['level'],
                    'text' => $request['text']
                ];
            }

        } else {
            $data = [
                'message' => 'Not found',
                'id' => $id
            ];
        }
        return response()->json($data, options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $row = Category::find($id);
        if ($row) {
            $row->delete();
            $data = [
                'message' => 'Deleted successfully',
                'id' => $id
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'id' => $id
            ];
        }

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }
}
