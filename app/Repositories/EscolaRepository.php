<?php

namespace App\Repositories;

use App\Models\Escola;

class EscolaRepository
{
    public function all()
    {
        return Escola::all();
    }

    public function find(int $id): ?Escola
    {
        return Escola::find($id);
    }

    public function create(array $data): Escola
    {
        return Escola::create($data);
    }

    public function update(int $id, array $data): ?Escola
    {
        $escola = Escola::find($id);
        if (!$escola) return null;

        $escola->update($data);
        return $escola;
    }

    public function delete(int $id): bool
    {
        $escola = Escola::find($id);
        if (!$escola) return false;

        return $escola->delete();
    }
}
