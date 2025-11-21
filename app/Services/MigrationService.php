<?php

namespace App\Services;

use App\Repositories\MigrationRepository;

class MigrationService
{
    protected $repository;

    public function __construct(MigrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }
}
