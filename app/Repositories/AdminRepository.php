<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository
{
    /** Buscar por ID */
    public function find(int $id): ?Admin
    {
        return Admin::find($id);
    }

    /** Buscar por Email */
    public function findByEmail(string $email): ?Admin
    {
        return Admin::where('email', $email)->first();
    }

    /** Listar todos */
    public function all()
    {
        return Admin::all();
    }

    /** Criar */
    public function create(array $data): Admin
    {
        return Admin::create($data);
    }

    /** Atualizar */
    public function update(int $id, array $data): ?Admin
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return null;
        }

        $admin->update($data);
        return $admin;
    }

    /** Deletar */
    public function delete(int $id): bool
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return false;
        }

        return $admin->delete();
    }
}
