<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [TokenController::class, 'getToken']);
Route::post('/tasks/show', [TaskController::class, 'index']);
Route::post('/tasks/create', [TaskController::class, 'create']);
Route::post('/tasks/update', [TaskController::class, 'update']);
Route::post('/tasks/destroy', [TaskController::class, 'destroy']);
