<?php

namespace App\Exceptions;

use App\Traits\Res;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use Res;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            $statusCode = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : 500;

            return $this->sendRes(
                $e->getMessage(),
                false,
                [],
                app()->isProduction()
                    ? []
                    : [
                        'exception' => class_basename($e),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],
                $statusCode
            );
        }

        return parent::render($request, $e);
    }

    /**
     * Handle unauthenticated users for API
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->sendRes(__('auth.unauthenticated'), false, [], [], 401);
        }

        return redirect()->guest(route('login'));
    }
}
