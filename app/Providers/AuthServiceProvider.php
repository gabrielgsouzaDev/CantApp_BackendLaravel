<?php

namespace App\Providers;

use App\Models\Pedido; // Importação da Model
use App\Policies\PedidoPolicy; // Importação da Policy
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // CRÍTICO R3: Mapeamento Pedido -> PedidoPolicy
        Pedido::class => PedidoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}