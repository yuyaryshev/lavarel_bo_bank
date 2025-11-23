<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
	Route::prefix('users')->group(function () {
		Route::get('{id}', [UserController::class, 'get']);
		Route::put('{id}', [UserController::class, 'update']);
		Route::post('{id}/deposit', [BankingController::class, 'deposit']);
		Route::post('{id}/transfer', [BankingController::class, 'transfer']);
	});
});
