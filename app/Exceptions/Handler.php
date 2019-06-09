<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // return parent::render($request, $exception);
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        } else if ($exception instanceof AuthorizationException) {
            return response()->json([
                'status' => '401',
                'error' => 'Not Authorized'
            ], 401);
        } else if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'status' => '404',
                'error' => 'Not Found'
            ], 404);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
