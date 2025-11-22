<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // 🌍 Middleware global — somente o essencial pra API
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class, 
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    // 👇 A parte mais importante:
    protected $middlewareGroups = [

        // ❌ WEB não será usado, mas deixamos vazio para não quebrar o core
        'web' => [
            // vazio — sem sessão, sem cookies, sem csrf, sem views
        ],

        // ✅ API limpo
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,

        // Sanctum — obrigatório para auth:sanctum
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

        // Caso precise em rotas futuras
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];
}
