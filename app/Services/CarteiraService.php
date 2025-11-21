<?php

namespace App\Services;

use App\Repositories\CarteiraRepository;

class CarteiraService
{
    protected $repository;

    public function __construct(CarteiraRepository $repository)
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

    public function findByUser(int $id_user)
    {
        return $this->repository->findByUser($id_user);
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
}
