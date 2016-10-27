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
use App\Http\RespondsWithJson;

class Handler extends ExceptionHandler
{
    use RespondsWithJson;

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
            // Route is not found, or route parameters invalid, or abort(404)
            case $e instanceof NotFoundHttpException:
                return $this->errorNotFound();

            // Route exists, but is called with the wrong HTTP verb
            case $e instanceof MethodNotAllowedHttpException:
                return $this->respondWithError('Requested method is not supported.', 'MethodNotAllowed')
                    ->setStatusCode(405);

            // Handle validation errors
            case $e instanceof ValidationException:
                return $this->errorInvalidParameter(
                    $e->validator->errors()->first(null, ':key'),
                    $e->validator->errors()->first()
                );

            // Handle all other abort helper calls, however, the developer
            // should rather use more specialized methods for erroring out
            case $e instanceof HttpException:
                return $this->respondWithError($e->getMessage(), 'GeneralError')
                    ->setStatusCode($e->getStatusCode());

            // Handle everything else
            default:
                return $this->respondWithError('The server encountered an internal error.', 'InternalError')
                    ->setStatusCode(500);
        }
    }
}
