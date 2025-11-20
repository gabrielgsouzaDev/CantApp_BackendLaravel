<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            // Erro de validação
            if ($exception instanceof ValidationException) {
                Log::warning('Erro de validação', $exception->errors());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro de validação',
                    'errors' => $exception->errors()
                ], 422);
            }

            // Não autenticado
            if ($exception instanceof AuthenticationException) {
                Log::warning('Tentativa não autenticada');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não autenticado'
                ], 401);
            }

            // Erro genérico
            Log::error('Erro interno', ['exception' => $exception->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() ?: 'Erro interno do servidor'
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
