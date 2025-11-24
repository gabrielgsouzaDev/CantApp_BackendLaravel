<?php

namespace App\Repositories;

use App\Models\Estoque;
use Illuminate\Support\Facades\DB;
use Exception;

class EstoqueRepository
{
    protected $model;

    public function __construct(Estoque $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('produto')->get();
    }

    public function find($id)
    {
        return $this->model->with('produto')->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $estoque = $this->model->find($id);
        if ($estoque) {
            $estoque->update($data);
            return $estoque;
        }
        return null;
    }

    public function delete($id)
    {
        $estoque = $this->model->find($id);
        if ($estoque) {
            return $estoque->delete();
        }
        return false;
    }

    /**
     * Decrementa estoque com validação
     */
    public function decrementStock(int $productId, int $quantity)
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $estoque = $this->model->find($productId);
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
     * Incrementa estoque (ex: pedido cancelado)
     */
    public function incrementStock(int $productId, int $quantity)
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $estoque = $this->model->find($productId);
            if (!$estoque) {
                throw new Exception("Estoque do produto não encontrado");
            }
            $estoque->quantidade += $quantity;
            $estoque->save();
            return $estoque;
        });
    }
}
