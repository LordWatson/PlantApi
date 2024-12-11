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
use Illuminate\Support\Facades\Hash;
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

    public function login(Request $request) : JsonResponse
    {
        // validate inputs
        $validated = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        // get the user
        $user = User::where('email', $validated['email'])->first();

        // check the password is correct
        if(!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'login' => false,
                'message' => 'Incorrect password',
            ], 401);
        }

        // create a token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'login' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
        ], 200);
    }
}
