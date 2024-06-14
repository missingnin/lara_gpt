<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WidgetAiController;
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
Route::post('/gpt_request/chat_event', [WidgetAiController::class, 'process']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/widget_settings', [AdminController::class, 'getWidgetSettings'])->name('getWidgetSettings');
Route::post('/widget_settings/update', [AdminController::class, 'updateWidgetSettings'])->name('updateWidgetSettings');
