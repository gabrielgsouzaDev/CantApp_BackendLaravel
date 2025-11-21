<?php

namespace App\Repositories;

use App\Models\ControleParentalProduto;

class ControleParentalProdutoRepository
{
    public function all()
    {
        return ControleParentalProduto::all();
    }

    public function find(int $id): ?ControleParentalProduto
    {
        return ControleParentalProduto::find($id);
    }

    public function create(array $data): ControleParentalProduto
    {
        return ControleParentalProduto::create($data);
    }

    public function update(int $id, array $data): ?ControleParentalProduto
    {
        $controleProduto = ControleParentalProduto::find($id);
        if (!$controleProduto) return null;

        $controleProduto->update($data);
        return $controleProduto;
    }

    public function delete(int $id): bool
    {
        $controleProduto = ControleParentalProduto::find($id);
        if (!$controleProduto) return false;

        return $controleProduto->delete();
    }
}
