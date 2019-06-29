<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\TokenTrait;

class UserController extends Controller
{
    use TokenTrait;

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'mobile_phone' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        User::create($request->all());
        return response()->json([
            "status" => true,
            "message" => "Registration successful."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function login(Request $request) {

        $validator = \Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        $user = User::where('email', $request['email'])->where('password', $request['password'])->first();

        if($user) {
            $passportToken = $this->createPassportTokenByUser($user, env('OAUTH_CLIENT_ID'));
            $bearerToken = $this->sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);
            $bearerToken = json_decode($bearerToken->getBody()->__toString(), true);

            return response()->json([
                "status" => true,
                "message" => "Log in successful!",
                $bearerToken
            ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');

        } else {
            return response()->json([
                "status" => false,
                "message" => "Given credentials do not match our records.",
                "user" => null
            ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        }
    }

    public function profile(Request $request)
    {
        $user_id = 1; //Auth::user()->id

        $user = User::whereId($user_id)->first();

        return response()->json([
            "status" => true,
            "user" => $user
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
