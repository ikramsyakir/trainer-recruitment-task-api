<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tasks\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('tasks', TaskController::class);
});
