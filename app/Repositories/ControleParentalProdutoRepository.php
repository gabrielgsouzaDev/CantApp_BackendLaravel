<?php

namespace App\Repositories;

use App\Models\ControleParentalProduto;

class ControleParentalProdutoRepository
{
    protected $model;

    public function __construct(ControleParentalProduto $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['controleParental', 'produto'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['controleParental', 'produto'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->model->find($id);
        if ($item) {
            $item->update($data);
            return $item;
        }
        return null;
    }

    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }
}
