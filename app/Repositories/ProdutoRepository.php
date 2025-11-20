<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    public function all()
    {
        return Produto::all();
    }

    public function find(int $id): ?Produto
    {
        return Produto::find($id);
    }

    public function findByCantina(int $idCantina)
    {
        return Produto::where('id_cantina', $idCantina)->get();
    }

    public function create(array $data): Produto
    {
        return Produto::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $produto = Produto::findOrFail($id);
        return $produto->update($data);
    }

    public function delete(int $id): bool
    {
        return Produto::destroy($id);
    }
}
