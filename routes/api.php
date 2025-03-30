<?php

use App\Models\Role;
use App\Services\Api\Clients\PerenualApiClient;
use App\Services\Api\Exceptions\ApiException;
use App\Services\Api\PerenualApiService;
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
        try {
            $apiService = new PerenualApiService(new PerenualApiClient());
            $response = $apiService->getSpecies();
            return response()->json($response->toArray());
        } catch (ApiException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    });
});
