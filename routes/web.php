<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

    Route::post('/dashboard/task/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/dashboard/task/destroy', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::post('/dashboard/task/get', [TaskController::class, 'get'])->name('task.get');
    Route::post('/dashboard/task/update', [TaskController::class, 'update'])->name('task.update');
});

require __DIR__.'/auth.php';
