<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/menu')->controller(MenuController::class)->group(function () {
    Route::get('/' , 'getAllData');
    Route::post('/create' , 'createData');
});

Route::prefix('v2/cart')->controller(CartController::class)->group(function () {
    Route::get('/' , 'getAllData');
    Route::post('/create' , 'createData');
    Route::get('/count' , 'countData');
});

