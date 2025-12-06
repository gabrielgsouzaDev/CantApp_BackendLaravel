<?php

namespace App\Repositories;

use App\Models\Carteira;
use Illuminate\Support\Facades\DB;
use Exception;

class CarteiraRepository
{
    protected $model;

    public function __construct(Carteira $model)
    {
        $this->model = $model;
    }
    
    // --- Métodos de Consulta ---

    public function findByUserId(int $userId): ?Carteira
    {
        return $this->model->where('id_user', $userId)->first();
    }
    
    /**
     * Busca a carteira de um usuário específica para atualização (lockForUpdate).
     * Essencial para evitar condições de corrida (R9) e DEVE ser chamado dentro de DB::transaction.
     *
     * @param int $userId
     * @return \App\Models\Carteira|null
     */
    public function findByUserIdForUpdate(int $userId): ?Carteira
    {
        // CRÍTICO R9/R23: Combina a busca e o bloqueio em UMA única consulta.
        // O Laravel garante o bloqueio apenas se a consulta for executada dentro de uma transação.
        try {
             return $this->model
                         ->where('id_user', $userId)
                         ->lockForUpdate() // Aplica o bloqueio pessimista na busca
                         ->firstOrFail(); // Usa findOrFail para lançar exceção se não encontrar (tratada no Service)
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Lança uma exceção genérica para o Service tratar se a carteira não existir
            throw new Exception("Carteira do usuário (ID: {$userId}) não encontrada.");
        }
    }
    
    // --- Métodos de Manipulação ---
    
    public function create(array $data): Carteira
    {
        return $this->model->create($data);
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
}