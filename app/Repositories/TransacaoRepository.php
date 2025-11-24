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
        return $this->model->with(['carteira', 'autor', 'aprovador'])->get();
    }

    public function find($id)
    {
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

    // Busca transações de um usuário específico
    public function getByUserId(string $userId)
    {
        return $this->model->where('id_user_autor', $userId)
            ->with(['carteira', 'autor', 'aprovador'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Busca transações associadas a pedidos de uma cantina específica
    public function getByCanteenId(string $cantinaId)
    {
        return $this->model
            ->whereHas('carteira', function ($q) use ($cantinaId) {
                $q->where('id_cantina', $cantinaId);
            })
            ->with(['carteira', 'autor', 'aprovador'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
