<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request) {

    	$credentials = Input::only('email', 'password');

    	if (! $token = JWTAuth::attempt($credentials)) {
			return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
		}

		return Response::json(compact('token'));

    }

    public function signup(Request $request) {

    	 $credentials = Input::only('email', 'password');

		try {
			$user = User::create($credentials);
		} catch (Exception $e) {
			return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
		}

		$token = JWTAuth::fromUser($user);

		return Response::json(compact('token'));

    }

    public function resetPassword(Request $request) {

    }

    public function requestPassword(Request $request) {

    }
}
