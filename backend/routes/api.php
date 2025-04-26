<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserTestController;
use App\Http\Controllers\TestQuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\QueryController;
use App\Http\Middleware\CheckAbilities;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/', function () {
    return 'API';
});

//QUERIES
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/getQuestionsWithTypesAndAnswers', [QueryController::class, 'index'])
        ->middleware(CheckAbilities::class.':*');
    
    Route::get('/getQuestionsWithTypesAndAnswers/{id}', [QueryController::class, 'show'])
        ->middleware(CheckAbilities::class.':*');
    
}); 
    
    Route::post('/users/login', [UserController::class, 'login']);
    Route::post('/users/logout', [UserController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
    Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':users:view');
    Route::post('/users', [UserController::class, 'store']);
    // ->middleware('auth:sanctum');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->middleware(CheckAbilities::class.':users:view');
    Route::patch('/users/{id}', [UserController::class, 'update'])
    ->middleware(CheckAbilities::class.':users:view');

//region roles

Route::get('roles', [RoleController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::get('roles/{id}', [RoleController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::post('roles', [RoleController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('roles/{id}', [RoleController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('roles/{id}', [RoleController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region categories
Route::get('categories', [CategoryController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':categories:view');
Route::get('categories/{id}', [CategoryController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':categories:view');
Route::post('categories', [CategoryController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('categories/{id}', [CategoryController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region sources
Route::get('sources', [SourceController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':sources:view');
Route::get('sources/{id}', [SourceController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':sources:view');
Route::post('sources', [SourceController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('sources/{id}', [SourceController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('sources/{id}', [SourceController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region question types
Route::get('questionTypes', [QuestionTypeController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::get('questionTypes/{id}', [QuestionTypeController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::post('questionTypes', [QuestionTypeController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('questionTypes/{id}', [QuestionTypeController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('questionTypes/{id}', [QuestionTypeController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region questions
Route::get('questions', [QuestionController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::get('questions/{id}', [QuestionController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::post('questions', [QuestionController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('questions/{id}', [QuestionController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('questions/{id}', [QuestionController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region userTests
Route::get('userTests', [UserTestController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':userTests:view');
Route::get('userTests/{id}', [UserTestController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':userTests:view');
Route::post('userTests', [UserTestController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':userTests:view');
Route::patch('userTests/{id}', [UserTestController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':userTests:view');
Route::delete('userTests/{id}', [UserTestController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':userTests:view');

//endregion

//region testQuestions 
Route::get('testQuestions', [TestQuestionController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::get('testQuestions/{id}', [TestQuestionController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':testQuestions:view');
Route::post('testQuestions', [TestQuestionController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':testQuestions:view');
Route::patch('testQuestions/{id}', [TestQuestionController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('testQuestions/{id}', [TestQuestionController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region answers 
Route::get('answers', [AnswerController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::get('answers/{id}', [AnswerController::class, 'show'])
    ->middleware('auth:sanctum', CheckAbilities::class.':answers:view');
Route::post('answers', [AnswerController::class, 'store'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::patch('answers/{id}', [AnswerController::class, 'update'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::delete('answers/{id}', [AnswerController::class, 'destroy'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion

//region admin interface

Route::get('/usersWithRoles', [UserRoleController::class, 'index'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');
Route::put('/usersWithRoles/{id}/role', [UserRoleController::class, 'updateRole'])
    ->middleware('auth:sanctum', CheckAbilities::class.':*');

//endregion
