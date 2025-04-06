<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function index()
{
    $query = "SELECT questions.id AS questionId, questions.question, questions.categoryId, question_types.questionCategory, questions.questionTypeId AS questionTypeId, answers.id AS answerId, answers.answer, answers.rightAnswer
              FROM questions
              LEFT JOIN question_types ON questions.questionTypeId = question_types.id
              LEFT JOIN answers ON questions.id = answers.questionId"; // Használj LEFT JOIN-ot, hogy akkor is jöjjenek vissza a kérdések, ha nincs válasz

    $rows = DB::select($query);

    $groupedQuestions = [];
    $questionMap = [];

    foreach ($rows as $row) {
        // Ha még nem találkoztunk a kérdéssel, hozzuk létre
        if (!isset($questionMap[$row->questionId])) {
            $questionMap[$row->questionId] = count($groupedQuestions);
            $groupedQuestions[] = [
                'questionId' => $row->questionId,
                'question' => $row->question,
                'categoryId' => $row->categoryId,
                'questionCategory' => $row->questionCategory,
                'questionTypeId' => $row->questionTypeId,
                'answers' => [], // Üres válaszok tömb, ha nincs válasz
            ];
        }

        // Ha van válasz, hozzáadjuk és beillesztjük a questionId-t
        if ($row->answerId) {
            $groupedQuestions[$questionMap[$row->questionId]]['answers'][] = [
                'answerId' => $row->answerId,
                'answer' => $row->answer,
                'rightAnswer' => $row->rightAnswer == 1 ? true : false,
                'questionId' => $row->questionId, // Hozzáadjuk a questionId-t
            ];
        }
    }

    // Még akkor is visszaküldjük a kérdéseket, ha nincs válaszuk
    $data = [
        'message' => 'ok',
        'data' => array_values($groupedQuestions), // A tömb indexeinek visszaállítása
    ];

    return response()->json($data, options: JSON_UNESCAPED_UNICODE);
}

public function show(int $id)
{
    // Módosított lekérdezés: LEFT JOIN, hogy akkor is visszakapjuk a kérdést, ha nincs válasz
    $query = "SELECT questions.id AS questionId, questions.question, questions.categoryId, question_types.questionCategory, 
                     questions.questionTypeId AS questionTypeId, answers.id AS answerId, answers.answer, answers.rightAnswer
              FROM questions
              LEFT JOIN question_types ON questions.questionTypeId = question_types.id
              LEFT JOIN answers ON questions.id = answers.questionId
              WHERE questions.id = :id";

    $rows = DB::select($query, ['id' => $id]);

    $groupedQuestions = [];
    $questionMap = [];

    foreach ($rows as $row) {
        // Ha még nem találkoztunk a kérdéssel, hozzuk létre
        if (!isset($questionMap[$row->questionId])) {
            $questionMap[$row->questionId] = count($groupedQuestions);
            $groupedQuestions[] = [
                'questionId' => $row->questionId,
                'question' => $row->question,
                'categoryId' => $row->categoryId,
                'questionCategory' => $row->questionCategory,
                'questionTypeId' => $row->questionTypeId,
                'answers' => [], // Üres válaszok tömb, ha nincs válasz
            ];
        }

        // Ha van válasz, hozzáadjuk és beillesztjük a questionId-t
        if ($row->answerId) {
            $groupedQuestions[$questionMap[$row->questionId]]['answers'][] = [
                'answerId' => $row->answerId,
                'answer' => $row->answer,
                'rightAnswer' => $row->rightAnswer == 1 ? true : false,
                'questionId' => $row->questionId, // Hozzáadjuk a questionId-t
            ];
        }
    }

    // Ha nem találtunk kérdést, 404-es hibát adunk vissza
    if (empty($groupedQuestions)) {
        return response()->json(['message' => 'No data found'], 404);
    }

    // A válaszok visszaadása
    $data = [
        'message' => 'ok',
        'data' => array_values($groupedQuestions), // A tömb indexeinek visszaállítása
    ];

    return response()->json($data, options: JSON_UNESCAPED_UNICODE);
}
}
