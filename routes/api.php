<?php

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\RolesEnum;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function ()
{
    Route::post('/logout',[\App\Http\Controllers\AuthController::class, 'logout']);

    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::apiResource('plants', \App\Http\Controllers\PlantController::class);

    Route::get('playground', function () {
        dd(Auth::user());
    });
});
