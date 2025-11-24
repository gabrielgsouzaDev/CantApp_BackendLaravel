<?php

namespace App\Services;

use App\Repositories\TransacaoRepository;

class TransacaoService
{
    protected $repository;

    public function __construct(TransacaoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    // Busca transações de um usuário específico
    public function getTransacoesByUserId(string $userId)
    {
        return $this->repository->getByUserId($userId);
    }

    // Busca transações relacionadas a uma cantina específica
    public function getTransacoesByCanteenId(string $cantinaId)
    {
        return $this->repository->getByCanteenId($cantinaId);
    }
}
