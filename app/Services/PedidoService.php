<?php

namespace App\Services;

use App\Repositories\PedidoRepository;

class PedidoService
{
    protected $repository;

    public function __construct(PedidoRepository $repository)
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

    public function produtos(int $id)
    {
        return $this->repository->produtos($id);
    }
}
