<?php

namespace App\Services;

use App\Repositories\CacheRepository;

class CacheService
{
    protected $repository;

    public function __construct(CacheRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($key)
    {
        return $this->repository->find($key);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($key, array $data)
    {
        return $this->repository->update($key, $data);
    }

    public function delete($key)
    {
        return $this->repository->delete($key);
    }
}
