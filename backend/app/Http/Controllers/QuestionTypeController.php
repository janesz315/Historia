<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use App\Http\Requests\StoreQuestionTypeRequest;
use App\Http\Requests\UpdateQuestionTypeRequest;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = QuestionType::all();
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionTypeRequest $request)
    {
        $rows = QuestionType::create(attributes: $request->all());
        return response()->json(['rows' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $rows = QuestionType::find($id);
        return response()->json(['rows' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionTypeRequest $request, int $id)
    {
        $row = QuestionType::find($id);
        if ($row) {

            try {
                $row->update($request->all());
                $data = [
                    'row' => $row
                ];
            } catch (\Illuminate\Database\QueryException $e) {
                $data = [
                    'message' => 'source incorrect',
                    'questionCategory' => $request['questionCategory']
                ];
            }

        } else {
            $data = [
                'message' => 'Not found',
                'id' => $id
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $row = QuestionType::find($id);
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