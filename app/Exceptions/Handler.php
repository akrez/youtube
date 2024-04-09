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
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof BadRequestException) {
            return $this->renderError($exception, 400);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->renderError($exception, 401);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof UnauthorizedException) {
            return $this->renderError($exception, 401);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->renderError($exception, 405);
        }

        if ($exception instanceof ValidationException) {
            return $this->renderError($exception, 422, null,  $exception->errors());
        }

        if ($exception instanceof ThrottleRequestsException) {
            return $this->renderError($exception, 429);
        }

        if ($exception instanceof Throwable) {
            return $this->renderError($exception, 500);
        }

        return parent::render($request, $exception);
    }

    private function renderError(Throwable $exception, $status, ?string $message = null, $errors = [])
    {
        return Response::status($status)
            ->message($message ? $message : $exception->getMessage())
            ->errors($errors);
    }
}
