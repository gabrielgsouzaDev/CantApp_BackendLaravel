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

    public function findByEscola(int $idEscola)
    {
        return Cantina::where('id_escola', $idEscola)->get();
    }

    public function create(array $data): Cantina
    {
        return Cantina::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $cantina = Cantina::findOrFail($id);
        return $cantina->update($data);
    }

    public function delete(int $id): bool
    {
        return Cantina::destroy($id);
    }
}
