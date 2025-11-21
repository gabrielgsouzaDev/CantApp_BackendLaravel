<?php

namespace App\Repositories;

use App\Models\ControleParental;

class ControleParentalRepository
{
    public function all()
    {
        return ControleParental::all();
    }

    public function find(int $id): ?ControleParental
    {
        return ControleParental::find($id);
    }

    public function create(array $data): ControleParental
    {
        return ControleParental::create($data);
    }

    public function update(int $id, array $data): ?ControleParental
    {
        $controle = ControleParental::find($id);
        if (!$controle) return null;

        $controle->update($data);
        return $controle;
    }

    public function delete(int $id): bool
    {
        $controle = ControleParental::find($id);
        if (!$controle) return false;

        return $controle->delete();
    }
}
