<?php

namespace App\Repositories;

use App\Models\Carteira;

class CarteiraRepository
{
    protected $model;

    public function __construct(Carteira $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['user', 'transacoes'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['user', 'transacoes'])->find($id);
    }

    public function findByUserId($userId)
    {
        return $this->model->with(['user', 'transacoes'])->where('id_user', $userId)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $carteira = $this->model->find($id);
        if ($carteira) {
            $carteira->update($data);
            return $carteira;
        }
        return null;
    }

    public function delete($id)
    {
        $carteira = $this->model->find($id);
        if ($carteira) {
            return $carteira->delete();
        }
        return false;
    }
}
