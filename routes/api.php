<?php

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

Route::get('/selector/lists', [\App\Http\Controllers\SelectorController::class, 'lists']);
Route::post('/selector/select', [\App\Http\Controllers\SelectorController::class, 'select']);
Route::get('/selector/goTo', [\App\Http\Controllers\SelectorController::class, 'goTo']);
Route::get('/geo/detect', [\App\Http\Controllers\GeoController::class, 'detect']);
Route::get('/geo/select', [\App\Http\Controllers\GeoController::class, 'select']);
