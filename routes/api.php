<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/marathons/{marathon:uuid}/tasks', [TaskController::class, 'index']);
Route::post('/marathons/{marathon:uuid}/tasks/{task}', [TaskController::class, 'store']);
Route::patch('/marathons/{marathon:uuid}/tasks/{task}', [TaskController::class, 'update']);
