<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Middleware\AuthenticateWithTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/token', [TokenController::class, 'getToken']);

Route::middleware([AuthenticateWithTokenMiddleware::class])->group(function () {
    Route::resource('/tasks', TaskController::class);
});
