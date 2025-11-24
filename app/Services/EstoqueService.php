<?php

namespace App\Services;

use App\Repositories\EstoqueRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class EstoqueService
{
    protected $repository;

    public function __construct(EstoqueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Decrementa o estoque de um produto de forma segura em transação
     */
    public function decrementStock(int $productId, int $quantity)
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $estoque = $this->repository->find($productId);
            if (!$estoque) {
                throw new Exception("Estoque do produto não encontrado");
            }
            if ($estoque->quantidade < $quantity) {
                throw new Exception("Estoque insuficiente");
            }
            $estoque->quantidade -= $quantity;
            $estoque->save();
            return $estoque;
        });
    }

    /**
     * Incrementa o estoque de um produto de forma segura (para estornos/cancelamentos)
     */
    public function incrementStock(int $productId, int $quantity)
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $estoque = $this->repository->find($productId);
            if (!$estoque) {
                throw new Exception("Estoque do produto não encontrado");
            }
            $estoque->quantidade += $quantity;
            $estoque->save();
            return $estoque;
        });
    }
}
