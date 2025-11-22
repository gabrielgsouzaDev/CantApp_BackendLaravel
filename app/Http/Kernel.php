<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // 🌍 Middleware global — tudo que deve rodar em TODAS as requisições
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    // 🗂 Grupos de middleware
    protected $middlewareGroups = [
        // ❌ Web não usado, deixamos vazio
        'web' => [],

        // ✅ API-only
        'api' => [
            \Fruitcake\Cors\HandleCors::class, // <-- obrigatório ser o primeiro
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // 📌 Middleware que podem ser aplicados por rota
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
