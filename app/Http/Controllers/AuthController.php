<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'user' => $user]);
    }

    /**
     * Register a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'user' => $user] , 201);
    }

    /**
     * Logout a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) : JsonResponse
    {

        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
