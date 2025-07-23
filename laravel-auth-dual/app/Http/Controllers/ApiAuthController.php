<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Option: Log in user immediately after registration for SPA convenience
        Auth::login($user); // This logs the user into the session

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the user using session-based authentication.
        // The second argument handles "Remember Me" functionality if provided by the frontend.
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Authentication successful. The user is now authenticated in the session.
        // Return user data or a simple success message to the frontend.
        return response()->json([
            'message' => 'Logged in successfully',
            'user' => Auth::user(),
        ]);
    }

    public function logout(Request $request)
    {
        // Log out the user from the 'web' guard (session-based authentication)
        Auth::guard('web')->logout();

        // Invalidate the current session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        // auth()->user() will automatically retrieve the authenticated user from the session
        // because of the 'auth:sanctum' middleware protecting this route.
        return response()->json($request->user());
    }
}
