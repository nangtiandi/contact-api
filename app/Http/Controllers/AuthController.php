<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreAuthRequest;
use App\Http\Requests\UpdateAuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register() {

    }
    public function login(Request $request)
    {
        return $request;
//        $credentials = $request->validate([
//            'email' => ['required', 'email'],
//            'password' => ['required'],
//        ]);
//        return $credentials;
//        if (Auth::attempt($credentials)) {
//            $token = Auth::user()->createToken('login-auth');
//            return response()->json([
//                'message' => 'login successed',
//                'token' => $token
//            ], 200);
//        }

//        return response()->json([
//            'message' => 'login failed!'
//        ],404);
    }
    public function logout(){
        \Illuminate\Support\Facades\Auth::user()->tokens()->delete();
        return response()->json(['message'=>"logout successful"]);
    }
}
