<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Aqui você registra serviços ou bindings no container, se precisar
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aqui você inicializa configurações globais ou eventos, se precisar
    }
}
