<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json(['status' => false, 'message' => 'Login credentials do not match our records'],
                Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'User successfully logged in',
            'token' => $request->user()->createToken('API Token')->plainTextToken,
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['status' => true, 'message' => 'User successfully registered'],
            Response::HTTP_CREATED);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => true, 'message' => 'User successfully logged out'], Response::HTTP_OK);
    }
}
