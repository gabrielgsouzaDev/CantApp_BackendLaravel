<?php

namespace App\Services;

use App\Repositories\UserDependenciaRepository;

class UserDependenciaService
{
    protected $repository;

    public function __construct(UserDependenciaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($responsavelId, $dependenteId)
    {
        return $this->repository->find($responsavelId, $dependenteId);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function delete($responsavelId, $dependenteId)
    {
        return $this->repository->delete($responsavelId, $dependenteId);
    }
}
