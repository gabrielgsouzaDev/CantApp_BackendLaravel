<?php

namespace App\Repositories;

use App\Models\UserRole;

class UserRoleRepository
{
    protected $model;

    public function __construct(UserRole $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['user', 'role'])->get();
    }

    public function find($userId, $roleId)
    {
        return $this->model->where('id_user', $userId)->where('id_role', $roleId)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete($userId, $roleId)
    {
        $record = $this->find($userId, $roleId);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
