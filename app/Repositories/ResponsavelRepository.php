<?php

namespace App\Repositories;

use App\Models\Responsavel;

class ResponsavelRepository
{
    public function all()
    {
        return Responsavel::all();
    }

    public function find(int $id): ?Responsavel
    {
        return Responsavel::find($id);
    }

    public function findByEmail(string $email): ?Responsavel
    {
        return Responsavel::where('email', $email)->first();
    }

    public function create(array $data): Responsavel
    {
        return Responsavel::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $responsavel = Responsavel::findOrFail($id);
        return $responsavel->update($data);
    }

    public function delete(int $id): bool
    {
        return Responsavel::destroy($id);
    }
}
