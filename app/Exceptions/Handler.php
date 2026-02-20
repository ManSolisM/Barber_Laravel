<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            // Log de CitaException como warning (no es crítico)
            if ($e instanceof CitaException) {
                Log::warning('Error en cita: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });

        $this->renderable(function (CitaException $e, $request) {
            // Si es una petición AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'success' => false
                ], 400);
            }

            // Si es una petición web normal, retornar con error
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        });
    }
}