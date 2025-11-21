<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    protected $model;

    public function __construct(Produto $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['cantina', 'itensPedido', 'estoque', 'controleParental'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['cantina', 'itensPedido', 'estoque', 'controleParental'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $produto = $this->model->find($id);
        if ($produto) {
            $produto->update($data);
            return $produto;
        }
        return null;
    }

    public function delete($id)
    {
        $produto = $this->model->find($id);
        if ($produto) {
            return $produto->delete();
        }
        return false;
    }
}
