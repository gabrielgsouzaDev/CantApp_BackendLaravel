<?php

namespace App\Repositories;

use App\Models\Carteira;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CarteiraRepository
{
    protected $model;

    public function __construct(Carteira $carteira)
    {
        $this->model = $carteira;
    }

    public function all()
    {
        return $this->model->with(['user', 'transacoes'])->get();
    }

    public function find($id)
    {
        // Usamos findOrFail para lançar 404 se não encontrado (melhor que find)
        return $this->model->with(['user', 'transacoes'])->findOrFail($id);
    }
    
    // Método de leitura padrão (sem bloqueio)
    public function findByUserId(int $userId): ?Carteira
    {
        return $this->model->where('id_user', $userId)->first();
    }

    /**
     * CRÍTICO: Busca a carteira pelo ID do usuário e aplica bloqueio pessimista (FOR UPDATE).
     */
    public function findByUserIdForUpdate(int $userId): ?Carteira
    {
        // lockForUpdate() é a chave para evitar problemas de concorrência no CarteiraService
        return $this->model->where('id_user', $userId)->lockForUpdate()->first();
    }

    public function create(array $data): Carteira
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Carteira
    {
        $carteira = $this->model->findOrFail($id); // Uso do findOrFail
        $carteira->update($data);
        return $carteira;
    }

    public function delete($id): bool
    {
        $carteira = $this->model->findOrFail($id); // Uso do findOrFail
        return $carteira->delete();
    }
    
    /**
     * CRÍTICO: Método simples para salvar a instância do Model no Service.
     */
    public function save(Carteira $carteira): bool
    {
        return $carteira->save();
    }
}