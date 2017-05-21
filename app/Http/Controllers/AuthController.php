<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\CreateUserRequest;


use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function login(Request $request) {

    	$credentials = $request->only('username', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'invalid_credentials' ,
                    "error_message" => "Wrong credentials"
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(array_merge(compact('token'), ["expires_in" => 7*24*60 ]));

    }

    public function signup(Request $request) {

        $user = new User($request->all());
        $user->password = bcrypt($user->password);
        $user->role = 'user';

		$user->save();
		
        try {

		  $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(array_merge(compact('token'), ["expires_in" => 7*24*60 ]));

    }

    public function resetPassword(Request $request) {

    }

    public function requestPassword(Request $request) {

    }
}
