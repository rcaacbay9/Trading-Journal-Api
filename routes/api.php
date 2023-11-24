<?php

use App\Http\Controllers\TradeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/createTrade',[TradeController::class,'createTrade']);
Route::get('/getTrade/{id}',[TradeController::class,'getTradeID']);
Route::put('/getTrade/edit/{id}',[TradeController::class,'saveEditTrade']);
Route::delete('/getTrade/delete/{id}',[TradeController::class,'deleteTrade']);

Route::get('/getTrades',[TradeController::class,'getTrades']);
Route::get('/getWinrate',[TradeController::class,'getWinrate']);
Route::get('/getPairData',[TradeController::class,'getPairData']);
Route::get('/getPerformanceData', [TradeController::class,'getPerformanceData']);
