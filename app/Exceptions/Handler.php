<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $e)
    {
        switch (true) {
            // Handle validation errors
            case $e instanceof ValidationException:
                $validation = $e->validator;
                return response()->json([
                    'error' => [
                        'code' => 'InvalidParameter',
                        'message' => $validation->errors()->first(),
                        'param' => $validation->errors()->first(null, ':key')
                    ]
                ], 400);

            // Route is not found, or route parameters invalid, or abort(404)
            case $e instanceof NotFoundHttpException:
                return response()->json([
                    'error' => [
                        'code' => 'ResourceNotFound',
                        'message' => 'Resource not found.'
                    ]
                ], 405);

            // Route exists, but is called with the wrong HTTP verb
            case $e instanceof MethodNotAllowedHttpException:
                return response()->json([
                    'error' => [
                        'code' => 'MethodNotAllowed',
                        'message' => 'Requested method is not supported.'
                    ]
                ], 405);

            // Handle all other abort helper calls, however, the developer
            // should rather use more specialized methods for erroring out
            case $e instanceof HttpException:
                return response()->json([
                    'error' => [
                        'code' => 'GeneralError',
                        'message' => $e->getMessage()
                    ]
                ], $e->getStatusCode());

            // Handle everything else
            default:
                return response()->json([
                    'error' => [
                        'code' => 'InternalError',
                        'message' => 'The server encountered an internal error. Please retry your request or '
                            . 'reach out to us for support.'
                    ]
                ], 500);
        }
    }
}
