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

    public function update(int $id, array $data): bool
    {
        $escola = Escola::findOrFail($id);
        return $escola->update($data);
    }

    public function delete(int $id): bool
    {
        return Escola::destroy($id);
    }
}
