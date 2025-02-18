<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/', function () {
    return 'API';
});

Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/logout', [UserController::class, 'logout']);
Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum');
Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store'])
    ->middleware('auth:sanctum');
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->middleware('auth:sanctum');
Route::patch('/users/{id}', [UserController::class, 'update'])
    ->middleware('auth:sanctum');

//region roles
Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{id}', [RoleController::class, 'show']);
Route::post('roles', [RoleController::class, 'store']);
// ->middleware('auth:sanctum');
Route::patch('roles/{id}', [RoleController::class, 'update']);
// ->middleware('auth:sanctum');
Route::delete('roles/{id}', [RoleController::class, 'destroy']);
// ->middleware('auth:sanctum');
// Route::apiResource('roles', RoleController::class);
//endregion

//region categories
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::post('categories', [CategoryController::class, 'store']);
// ->middleware('auth:sanctum');
Route::patch('categories/{id}', [CategoryController::class, 'update']);
// ->middleware('auth:sanctum');
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
// ->middleware('auth:sanctum');
// Route::apiResource('roles', RoleController::class);
//endregion

//region sources
Route::get('sources', [SourceController::class, 'index']);
Route::get('sources/{id}', [SourceController::class, 'show']);
Route::post('sources', [SourceController::class, 'store']);
// ->middleware('auth:sanctum');
Route::patch('sources/{id}', [SourceController::class, 'update']);
// ->middleware('auth:sanctum');
Route::delete('sources/{id}', [SourceController::class, 'destroy']);
// ->middleware('auth:sanctum');
// Route::apiResource('sources', SourceController::class);
//endregion

//region question types
Route::get('questionCategory', [QuestionTypeController::class, 'index']);
Route::get('questionCategory/{id}', [QuestionTypeController::class, 'show']);
Route::post('questionCategory', [QuestionTypeController::class, 'store']);
// ->middleware('auth:sanctum');
Route::patch('questionCategory/{id}', [QuestionTypeController::class, 'update']);
// ->middleware('auth:sanctum');
Route::delete('questionCategory/{id}', [QuestionTypeController::class, 'destroy']);
// ->middleware('auth:sanctum');
// Route::apiResource('diaks', QuestionTypeController::class);
//endregion