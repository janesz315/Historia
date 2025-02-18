<?php

use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/', function(){
    return 'API';
});

//region roles
Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{id}', [RoleController::class, 'show']);
Route::post('roles', [RoleController::class, 'store']);
    // ->middleware('auth:sanctum');
Route::patch('roles/{id}', [RoleController::class, 'update']);
    // ->middleware('auth:sanctum');
Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    // ->middleware('auth:sanctum');
// Route::apiResource('diaks', DiakController::class);
//endregion
