<?php

use App\Http\Controllers\StoreBalanceController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('user', UserController::class);
Route::get('user/all/paginated', UserController::class . '@getAllPaginated');

Route::apiResource('store', StoreController::class);
Route::get('store/all/paginated', StoreController::class . '@getAllPaginated');
Route::post('store/{id}/verified', StoreController::class . '@updateVerifiedStatus');

Route::apiResource('store-balance', StoreBalanceController::class)->except(['store', 'update', 'delete']);
Route::get('store-balance/all/paginated', StoreBalanceController::class . '@getAllPaginated');
