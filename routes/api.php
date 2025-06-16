<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;

Route::middleware('auth:sanctum')->prefix('clients')->group(function () {
    Route::delete('/{id}', [ClientController::class, 'destroy']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::post('/', [ClientController::class, 'store']);
    Route::get('/', [ClientController::class, 'index']);
});

Route::middleware('auth:sanctum')->prefix('dashboard')->group(function () {
    Route::get('/', [ClientController::class, 'dashboard']);
});

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::patch('/{id}/password', [UserController::class, 'updatePassword']);
});

Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/create-base', [UserController::class, 'createBaseUser']);
