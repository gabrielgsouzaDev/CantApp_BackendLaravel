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

    /**
     * CRÍTICO R20: Corrigido. A chamada agora delega a query ao Repositório.
     * O método 'model()' foi removido daqui.
     */
    public function getTransactionsByUser(string $userId)
    {
        // Chama o método no Repositório que irá construir a query
        return $this->repository->getByUser($userId);
    }

    /**
     * CRÍTICO R18/R20: Método renomeado para convenção correta e delega ao Repositório.
     */
    public function getTransactionsByCanteenId(string $cantinaId)
    {
        return $this->repository->getByCanteenId($cantinaId);
    }
}