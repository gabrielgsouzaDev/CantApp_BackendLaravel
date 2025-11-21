<?php

namespace App\Repositories;

use App\Models\Cantina;

class CantinaRepository
{
    public function all()
    {
        return Cantina::all();
    }

    public function find(int $id): ?Cantina
    {
        return Cantina::find($id);
    }

    public function create(array $data): Cantina
    {
        return Cantina::create($data);
    }

    public function update(int $id, array $data): ?Cantina
    {
        $cantina = Cantina::find($id);
        if (!$cantina) return null;

        $cantina->update($data);
        return $cantina;
    }

    public function delete(int $id): bool
    {
        $cantina = Cantina::find($id);
        if (!$cantina) return false;

        return $cantina->delete();
    }

    /** Produtos da cantina */
    public function produtos(int $id)
    {
        $cantina = Cantina::find($id);
        return $cantina ? $cantina->produtos : null;
    }
}
