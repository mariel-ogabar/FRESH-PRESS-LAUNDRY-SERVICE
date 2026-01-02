<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => 'required|string|in:ADMIN,STAFF,CUSTOMER' // Ensuring role assignment
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => $fields['role']
        ]);

        $token = $user->createToken('freshpresstoken')->plainTextToken;

        return response(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }

        $token = $user->createToken('freshpresstoken')->plainTextToken;

        return response(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request) 
    {
        // 1. Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logged out successfully. Token has been invalidated.'
        ], 200);
    }
    
}