<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User; // CRÍTICO: Importar o Model User
use App\Observers\UserObserver; // CRÍTICO: Importar o Observer

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Adicionar Listeners de eventos customizados aqui, se necessário.
    ];

    /**
     * The model observers for your application.
     * CRÍTICO: Mapeamento da Model User para o UserObserver.
     *
     * @var array
     */
    protected $observers = [
        // O Laravel mapeia o Model (Chave) para a classe Observer (Valor).
        User::class => [
            UserObserver::class,
        ],
    ];


    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Necessário chamar o método pai para registrar as Policies e Observers.
        parent::boot(); 
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}