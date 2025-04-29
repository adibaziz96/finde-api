<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = $this->auth->register($validated);

            return response()->json(['message' => 'Registered successfully', 'user' => $user]);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $authData = $this->auth->login($validated);
    
            return response()->json($authData);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->auth->logout($request->user());
    
            return response()->json(['message' => 'Logged out successfully']);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
