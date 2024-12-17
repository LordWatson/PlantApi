<?php

use App\Enums\RolesEnum;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function ()
{
    Route::post('/logout',[\App\Http\Controllers\AuthController::class, 'logout']);

    Route::apiResource('users', \App\Http\Controllers\UserController::class)
        ->middleware('can:viewAny,App\Models\User');

    Route::get('playground', function () {
        dd(Auth::user());
        $role = Role::where('name', RolesEnum::Admin)
            ->select('level')
            ->first();
        dd($role);
    });
});
