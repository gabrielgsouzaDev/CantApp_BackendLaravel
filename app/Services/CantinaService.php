<?php

namespace App\Services;

use App\Repositories\CantinaRepository;

class CantinaService
{
    protected $repository;

    public function __construct(CantinaRepository $repository)
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

    // ✅ ADICIONADO — usado no controller
    public function getBySchool($id_escola)
    {
        return $this->repository->getBySchool($id_escola);
    }
}
