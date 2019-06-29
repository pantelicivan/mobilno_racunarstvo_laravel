<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        User::create($request->all());
        return response()->json([
            "status" => true,
            "message" => "Registration successful."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function login(Request $request) {
        $user = User::where('email', $request['email'])->where('password', $request['password'])->first();

        if($user) {
            return response()->json([
                "status" => true,
                "message" => "Log in successful!",
                "user" => json_encode($user)
            ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        } else {
            return response()->json([
                "status" => false,
                "message" => "Given credentials do not match our records.",
                "user" => null
            ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        }
    }
}
