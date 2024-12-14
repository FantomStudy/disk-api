<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function registration(StoreUserRequest $request){
        $user = User::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "success",
            "token" => $user->createToken("$user->first_name's reg token")->plainTextToken,
        ], 201);
    }
    public function login(LoginUserRequest $request){
        $attempt = Auth::attempt($request->only('email', 'password'));
        $user = Auth::user();
        if ($attempt) {
            return response()->json([
                "success" => true,
                "message" => "success",
                "token" => $user->createToken("$user->first_name's token")->plainTextToken,
            ]);
        }

    }
    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "success" => true,
            "message" => "logout",
        ], 201);
    }

}
