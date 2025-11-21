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

    public function create(array $data): Produto
    {
        return Produto::create($data);
    }

    public function update(int $id, array $data): ?Produto
    {
        $produto = Produto::find($id);
        if (!$produto) return null;

        $produto->update($data);
        return $produto;
    }

    public function delete(int $id): bool
    {
        $produto = Produto::find($id);
        if (!$produto) return false;

        return $produto->delete();
    }
}
