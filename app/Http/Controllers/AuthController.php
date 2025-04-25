<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\AuthServiceInterface;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $this->auth->register($validated);

        return response()->json(['message' => 'Registered successfully', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $authData = $this->auth->login($validated);

        return response()->json($authData);
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
