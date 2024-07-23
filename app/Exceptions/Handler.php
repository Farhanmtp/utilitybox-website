<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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

    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\Console\Exception\InvalidOptionException::class,
        \Symfony\Component\Console\Exception\InvalidArgumentException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have the required authorization.',
                    'status' => 403,
                ]);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        $code = $this->errorCodeFromException($e);

        if ($code == 404 && !(isAdminUri() || request()->is('api/*'))) {
            return Inertia::render('Errors/404', [
                'status' => $code,
                'message' => $e->getMessage(),
            ])->toResponse($request)->setStatusCode($code);
        }

        if (!$request->header('x-inertia') && ($request->ajax() || $request->wantsJson() || $request->segment(1) == 'api')) {

            if ($e instanceof ValidationException) {
                $code = 422;
            }

            $json = [
                'success' => false,
                'message' => $e->getMessage(),
            ];

            if ($code == 422) {
                $json['errors'] = $e->errors();
            } elseif (!app()->isProduction()) {
                $json['file'] = $e->getFile();
                $json['line'] = $e->getLine();
            }

            return response()->json($json, $code);
        }

        // Show HTTP exceptions
        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        }

        // Show Token exceptions
        if ($e instanceof TokenMismatchException) {
            $message = __('Your session has expired. Please try again.');
            if (isAdminUri()) {
                Alert::error($message)->flash();
                return redirect(URL::previous())->withInput();
            } else {
                return redirect(URL::previous())->withInput()->withErrors($message);
            }
        }

        // Show MethodNotAllowed HTTP exceptions
        if ($e instanceof MethodNotAllowedHttpException) {
            $message = "Opps! Seems you use a bad request method. Please try again.";

            return redirect(URL::previous())->withInput()->withErrors($message);
        }

        return parent::render($request, $e);
    }

    /**
     * Get the view used to render HTTP exceptions.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     * @return string|null
     */
    protected function getHttpExceptionView(HttpExceptionInterface $e)
    {
        if (isAdminUri() && auth()->check()) {
            $view = 'admin.errors.' . $e->getStatusCode();
            if (view()->exists($view)) {
                return $view;
            }
        }

        return parent::getHttpExceptionView($e);
    }

    /**
     * @param \Throwable $e
     * @return int
     */
    protected function errorCodeFromException(Throwable $e)
    {
        if ($this->isHttpException($e)) {
            return $e->getStatusCode();
        }

        return $e->getCode();
    }
}
