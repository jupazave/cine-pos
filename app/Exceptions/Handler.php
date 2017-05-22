<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        switch ($exception->getStatusCode()) {
            case 400: {
                return response()->json([
                    'error' => 'bad_request',
                    'error_message' => 'Usually caused by invalid input data (missing arguments, invalid arguments 
                    values, etc.). Cause of error is described in response.'
                ]);
            }
            case 401: {
                return response()->json([
                    'error' => 'unauthorized',
                    'error_message' => 'Authentication failed.'
                ]);
            }
            case 403: {
                return response()->json([
                    'error' => 'forbidden',
                    'error_message' => 'You don\'t have access to resource.'
                ]);
            }
            case 404: {
                return response()->json([
                    "error" => "not_found",
                    "error_message" => 'You\'re asking for something that doesn\'t exist.'
                ], 404);
            }
            case 500: {
                return response()->json([
                    'error' => 'internal_server_error',
                    'error_message' => 'Something went wrong. We are sorry, it is our fault and we will 
                    make our best to fix it!'
                ]);
            }
            case 503: {
                return response()->json([
                    'error' => 'temporary_unavailable',
                    'error_message' => 'This response is typically returned when system is under maintenance. 
                    Maintenance reason and expected maintenance ent time are also returned in response.'
                ]);
            }

        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        

        return redirect()->guest(route('login'));
    }
}
