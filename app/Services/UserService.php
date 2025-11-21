<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
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

    public function findByEmail(string $email)
    {
        return $this->repository->findByEmail($email);
    }

    public function create(array $data)
    {
        if (isset($data['senha_hash'])) {
            $data['senha_hash'] = Hash::make($data['senha_hash']);
        }
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['senha_hash'])) {
            $data['senha_hash'] = Hash::make($data['senha_hash']);
        }
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
