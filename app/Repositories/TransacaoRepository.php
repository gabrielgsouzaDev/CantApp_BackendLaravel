<?php

namespace App\Repositories;

use App\Models\Transacao;

class TransacaoRepository
{
    protected $model;

    public function __construct(Transacao $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        // R4: Garantia de Eager Loading
        return $this->model->with(['carteira', 'autor', 'aprovador'])->get();
    }

    public function find($id)
    {
        // R4: Garantia de Eager Loading
        return $this->model->with(['carteira', 'autor', 'aprovador'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transacao = $this->model->find($id);
        if ($transacao) {
            $transacao->update($data);
            return $transacao;
        }
        return null;
    }

    public function delete($id)
    {
        $transacao = $this->model->find($id);
        if ($transacao) {
            return $transacao->delete();
        }
        return false;
    }

    /**
     * CRÍTICO R20: Método criado para encapsular a lógica de query, resolvendo a falha.
     */
    public function getByUser(string $userId)
    {
        return $this->model->where('id_user_autor', $userId)
            // Incluído OR para transações aprovadas por ele (opcional, mas robusto)
            ->orWhere('id_aprovador', $userId) 
            ->with(['carteira', 'autor', 'aprovador'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Relação por Cantina: Assume que a cantina é relevante para a transação.
     */
    public function getByCanteenId(string $cantinaId)
    {
        // Relação complexa: Busca transações cuja carteira está associada a um usuário que trabalha naquela cantina
        // OU busca transações ligadas a pedidos daquela cantina (via Tabela Pedido).
        // Aqui, usaremos a lógica fornecida, mas adicionando uma busca robusta no 'autor'
        
        return $this->model
            // Opção 1: Filtrar pela carteira do autor
            ->whereHas('autor', function ($q) use ($cantinaId) {
                 $q->where('id_cantina', $cantinaId);
            })
            // Opção 2: Filtrar por pedidos (assumindo que 'referencia' é um Pedido ID)
            // ->orWhereHas('pedido', ...) 
            ->with(['carteira', 'autor', 'aprovador'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}