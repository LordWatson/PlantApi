<?php
/*
 * Test directory - tests/Feature/Auth
 * */

namespace App\Http\Controllers;

use App\Actions\User\CreateUserAction;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, CreateUserAction $action) : JsonResponse
    {
        // validate the request
        $validated = $request->validated();

        // create the user
        $user = $action->execute($validated);

        // create a login token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'registered' => true,
            'message' => 'User registered successfully',
            'token' => $token,
        ], 201);
    }
}
