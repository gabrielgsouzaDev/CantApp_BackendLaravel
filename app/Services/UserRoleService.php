<?php

namespace App\Services;

use App\Repositories\UserRoleRepository;

class UserRoleService
{
    protected $repository;

    public function __construct(UserRoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($userId, $roleId)
    {
        return $this->repository->find($userId, $roleId);
    }

    public function assignRole(array $data)
    {
        return $this->repository->create($data);
    }

    public function removeRole($userId, $roleId)
    {
        return $this->repository->delete($userId, $roleId);
    }
}
