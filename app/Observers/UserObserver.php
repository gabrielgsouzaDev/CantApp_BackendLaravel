<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Carteira; // Importar a Model Carteira (Obrigatório)

class UserObserver
{
    /**
     * Handle the User "created" event.
     * CRÍTICO: Garante a Integridade Referencial (User -> Carteira).
     * @param User $user
     */
    public function created(User $user): void
    {
        // Risco: Se o usuário já tiver uma carteira (ex: seeding), esta checagem previne erro.
        if (!$user->carteira) { 
            // 1. Cria a carteira vinculada ao usuário com saldo zero (R$ 0,00).
            // O relacionamento carteira() está no Model User.
            $user->carteira()->create([
                'saldo' => 0.00,
                'saldo_bloqueado' => 0.00,
                // Se 'limite_recarregar' e 'limite_maximo_saldo' forem obrigatórios,
                // eles devem ter valores padrão no DB ou ser definidos aqui.
            ]);
            
            // CRÍTICO R10: Opcional, mas útil: Adicionar um log inicial de criação da carteira.
        }
    }

    // Se necessário, você pode adicionar lógica de estorno/limpeza aqui:
    // public function deleted(User $user): void
    // {
    //     // Lógica para Soft Delete ou limpeza de dados relacionados à carteira, se necessário.
    // }
}