<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $token = Auth::user()->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        return response()->json([
            'message' => trans('auth.failed')
        ], 401);
    }
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        auth()->logout();

        return response()->json([
            'message' => trans('auth.logout'),
        ]);
    }
}
