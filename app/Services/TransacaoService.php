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

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        // Aqui você pode incluir regras de negócio, tipo:
        // validar saldo, tipo de transação etc.
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
