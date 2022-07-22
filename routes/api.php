<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::apiResource('messages', MessageController::class, ['only' => ['index', 'store', 'show']]);
Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages/store', [MessageController::class, 'store']);
Route::get('/messages/consume', [MessageController::class, 'consume']);
Route::get('/messages/total', [MessageController::class, 'total']);
