<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('/todos')->controller(TodoController::class)->group(function () {
    Route::get('/', 'list');
    Route::put('/', 'create');
    Route::get('/{id}', 'details');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'delete');
})->middleware('auth:sanctum');