<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function registration(StoreUserRequest $request){
        $user = User::create($request->all());
        $token = $user->createToken("register token")->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => "Welcome, $user->first_name!",
            'token' => $token,
        ]);
    }
    public function login(LoginUserRequest $request){
        $attempt = Auth::attempt($request->only('email', 'password'));
        if($attempt){
            $user = Auth::user();
            $token = $user->createToken("login token")->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => "Welcome, $user->first_name!",
                'token' => $token,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "Login failed",
        ], 401);
    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "success" => true,
            "message" => "Logged out successfully."
        ], 201);
    }
}
