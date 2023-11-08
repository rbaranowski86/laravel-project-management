<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->group(function () {
    // Place all routes that require authentication here
    Route::apiResource('projects', ProjectController::class)->except(['show']);
    Route::get('projects/{project}', [ProjectController::class, 'show'])
        ->middleware('checkProjectDeadline');

    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/assign/{user?}', [TaskController::class, 'assign'])->name('tasks.assign');
    Route::get('projects/statistics', [ProjectController::class, 'statistics']);
});
