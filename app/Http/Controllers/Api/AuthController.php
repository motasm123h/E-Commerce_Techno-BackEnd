<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle administrative token login requests.
     */
    public function login(Request $request)
    {
        // 1. Validate incoming form inputs
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Authenticate the database records
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.'
            ], 422);
        }

        // 3. Generate an explicit plain-text Sanctum token
        $user = Auth::user();
        $token = $user->createToken('admin-access-token')->plainTextToken;

        // 4. Send both user profile and token directly back to React
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Terminate user access state.
     */
    public function logout(Request $request)
    {
        // Revoke the specific token used to access this request route
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }
}