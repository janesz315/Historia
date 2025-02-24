<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Answer::all();
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
    public function store(StoreAnswerRequest $request)
    {
        $rows = Answer::create(attributes: $request->all());
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $rows = Answer::find($id);
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerRequest $request, int $id)
    {
        $row = Answer::find($id);
        if ($row) {

            try {
                $row->update($request->all());
                $data = [
                    'row' => $row
                ];
            } catch (\Illuminate\Database\QueryException $e) {
                $data = [
                    'message' => 'source incorrect',
                    'answer' => $request['answer'],
                    'questionsId' => $request['questionId'],
                    'rightAnswer' => $request['rightAnswer'],
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
        $row = Answer::find($id);
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
