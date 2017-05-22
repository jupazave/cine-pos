<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class CheckJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($request->header('authorization') == null) {
            return response()->json(['error'=>'credentials_required', 'error_description' => 'No authorization token was found']);
        }

        $parts = explode(" ", $request->header('authorization'));

        if(count($parts) != 2){
            return response()->json(['error'=>'credentials_bad_scheme', 'error_description' => 'Format is Authorization: Bearer [token]']);
        }

        $scheme = $parts[0];
        $credentials = $parts[1];

        if (preg_match("/^Bearer$/i", $scheme)) {
            $token = $credentials;
        } else {
            return response()->json(['error'=>'credentials_bad_format', 'error_description' => 'Format is Authorization: Bearer [token]']);
        }

        try {
            $user = JWTAuth::toUser($token);
        } catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['token_expired'], $e->getStatusCode());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['token_invalid'], $e->getStatusCode());
            }else{
                return response()->json(['error'=>'credentials_required', 'error_description' => 'No authorization token was found']);
            }
        }
        $request->attributes->add(['user' => $user]);
        return $next($request);
    }
}