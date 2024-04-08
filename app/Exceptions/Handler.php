<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Facades\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render($request, Throwable $e)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return $this->handle($e, $e->getMessage(), 401);
        };
        if ($e instanceof UnauthorizedException) {
            return $this->handle($e, $e->getMessage(), 401);
        };
        if ($e instanceof NotFoundHttpException) {
            return $this->handle($e, $e->getMessage(), 404);
        };
        if ($e instanceof NotFoundHttpException) {
            return $this->handle($e, $e->getMessage(), 404);
        };
        if ($e instanceof ModelNotFoundException) {
            return $this->handle($e, $e->getMessage(), 404);
        };
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->handle($e, $e->getMessage(), 405);
        };
        if ($e instanceof ValidationException) {
            return $this->handle($e, $e->getMessage(), 422, $e->errors());
        };
        if ($e instanceof ThrottleRequestsException) {
            return $this->handle($e, $e->getMessage(), 429);
        };
        if ($e instanceof Throwable) {
            return $this->handle($e, $e->getMessage(), 500);
        };

        return parent::render($request, $e);
    }

    public function handle($e, $message, $status, $errors = [])
    {
        return Response::status($status)
            ->message($message)
            ->errors($errors);
    }
}
