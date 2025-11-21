<?php

namespace App\Repositories;

use App\Models\Estoque;

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
}
