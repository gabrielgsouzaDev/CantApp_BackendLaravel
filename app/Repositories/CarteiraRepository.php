<?php

namespace App\Repositories;

use App\Models\Carteira;
use Illuminate\Support\Facades\DB;

class CarteiraRepository
{
    protected $model;

    public function __construct(Carteira $model)
    {
        $this->model = $model;
    }

    /**
     * Busca a carteira de um usuário específica para atualização (lockForUpdate).
     * Essencial para evitar condições de corrida (race conditions) em operações financeiras.
     *
     * @param int $userId
     * @return \App\Models\Carteira|null
     */
    public function findByUserIdForUpdate(int $userId): ?Carteira
    {
        // 1. Encontra a carteira pelo ID do usuário
        // CORRIGIDO: Usando 'id_user' em vez de 'user_id' para alinhar com o esquema de banco de dados não-padrão.
        $carteira = $this->model->where('id_user', $userId)->first();
        
        if ($carteira) {
            // 2. Bloqueia a linha no banco de dados.
            // Isso DEVE ser executado dentro de uma transação DB::transaction.
            return $this->model
                        ->where('id_carteira', $carteira->id_carteira)
                        ->lockForUpdate() // Aplica o bloqueio pessimista
                        ->first();
        }
        
        return null;
    }

    /**
     * Salva ou atualiza uma instância de Carteira (usado para salvar o novo saldo).
     *
     * @param \App\Models\Carteira $carteira
     * @return bool
     */
    public function save(Carteira $carteira): bool
    {
        return $carteira->save();
    }
    
    // Outros métodos CRUD podem ser adicionados aqui conforme o MVP exigir...
    
    public function findByUserId(int $userId): ?Carteira
    {
        // CORRIGIDO: Usando 'id_user' em vez de 'user_id'
        return $this->model->where('id_user', $userId)->first();
    }
    
    public function create(array $data): Carteira
    {
        return $this->model->create($data);
    }
}