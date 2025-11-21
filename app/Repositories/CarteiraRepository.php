<?php

namespace App\Repositories;

use App\Models\Carteira;

class CarteiraRepository
{
    public function all()
    {
        return Carteira::all();
    }

    public function find(int $id): ?Carteira
    {
        return Carteira::find($id);
    }

    public function findByUser(int $id_user): ?Carteira
    {
        return Carteira::where('id_user', $id_user)->first();
    }

    public function create(array $data): Carteira
    {
        return Carteira::create($data);
    }

    public function update(int $id, array $data): ?Carteira
    {
        $carteira = Carteira::find($id);
        if (!$carteira) return null;

        $carteira->update($data);
        return $carteira;
    }

    public function delete(int $id): bool
    {
        $carteira = Carteira::find($id);
        if (!$carteira) return false;

        return $carteira->delete();
    }
}
