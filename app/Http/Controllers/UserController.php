<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use App\User;
use App\TokenTrait;
use App\Token;

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

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        User::create($input);
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

        $user = User::where('email', $request['email'])->first();

        if(!Hash::check($request['password'], $user->password)) {
            $user = null;
        }

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
        $user_id = Auth::user()->id;

        $user = User::whereId($user_id)->first();

        return response()->json([
            "status" => true,
            "user" => $user
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function logout(Request $request)
    {
        Token::where('user_id', Auth::user()->id)->delete();

        return response()->json([
            "status" => true,
            "message" => 'Log out successful!'
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
