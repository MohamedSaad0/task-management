<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tasks',[TaskController::class, 'index']);
    Route::post('/tasks',[TaskController::class, 'store']);
    Route::put('/tasks/{task}',[TaskController::class, 'update']);

    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::post('/tasks/{task}/dependencies', [TaskController::class, 'assignDependencies']);
});
