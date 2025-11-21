<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /** Listar todos usuários */
    public function all()
    {
        return User::all();
    }

    /** Buscar por ID */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /** Buscar por email */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /** Criar usuário */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /** Atualizar usuário */
    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        $user->update($data);
        return $user;
    }

    /** Deletar usuário */
    public function delete(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    /** Buscar usuários por escola */
    public function findByEscola(int $id_escola)
    {
        return User::where('id_escola', $id_escola)->get();
    }

    /** Buscar dependentes de um responsável */
    public function findDependentes(int $id_responsavel)
    {
        return User::whereHas('dependentes', function($q) use ($id_responsavel) {
            $q->where('id_responsavel', $id_responsavel);
        })->get();
    }
}
