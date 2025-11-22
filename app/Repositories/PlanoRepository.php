<?php

namespace App\Repositories;

use App\Models\Plano;

class PlanoRepository
{
    protected $model;

    public function __construct(Plano $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('escolas')->get();
    }

    public function find($id)
    {
        return $this->model->with('escolas')->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $plano = $this->model->find($id);
        if ($plano) {
            $plano->update($data);
            return $plano;
        }
        return null;
    }

    public function delete($id)
    {
        $plano = $this->model->find($id);
        if ($plano) {
            return $plano->delete();
        }
        return false;
    }
}
