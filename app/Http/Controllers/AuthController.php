<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Create a new user
        $user = User::query()->create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Generate a new access token for the user
        $accessToken = $user->createToken('authToken')->plainTextToken;

        // Return the user data and access token
        return $this->jsonResponse(
            data: [
                'user' => $user,
                'access_token' => $accessToken,
            ],
            message: 'User registered successfully.',
            status: 201
        );
    }
    
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($validatedData)) {
            return $this->jsonResponse(
                message: 'Invalid credentials.',
                status: 401
            );
        }

        $user = Auth::user();

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return $this->jsonResponse(
            data: [
                'user' => $user,
                'access_token' => $accessToken,
            ],
            message: 'User logged in successfully.',
            status: 200
        );
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return $this->jsonResponse(
            message: 'User logged out successfully.',
            status: 200
        );
    }
}
