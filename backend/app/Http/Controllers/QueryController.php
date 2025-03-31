<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function getQuestionsWithTypesAndAnswers()
{
    $query = "SELECT questions.id AS questionId, questions.question, questions.categoryId, question_types.questionCategory, answers.id AS answerId, answers.answer, answers.rightAnswer
              FROM questions
              JOIN question_types ON questions.questionTypeId = question_types.id
              JOIN answers ON questions.id = answers.questionId";

    $rows = DB::select($query);

    $groupedQuestions = [];
    $questionMap = [];

    foreach ($rows as $row) {
        if (!isset($questionMap[$row->questionId])) {
            $questionMap[$row->questionId] = count($groupedQuestions);
            $groupedQuestions[] = [
                'questionId' => $row->questionId,
                'question' => $row->question,
                'categoryId' => $row->categoryId,
                'questionCategory' => $row->questionCategory,
                'answers' => [],
            ];
        }

        $groupedQuestions[$questionMap[$row->questionId]]['answers'][] = [
            'answerId' => $row->answerId,
            'answer' => $row->answer,
            'rightAnswer' => $row->rightAnswer
        ];
    }

    $data = [
        'message' => 'ok',
        'data' => array_values($groupedQuestions), // A tömb indexeinek visszaállítása
    ];

    return response()->json($data, options: JSON_UNESCAPED_UNICODE);
}
}
