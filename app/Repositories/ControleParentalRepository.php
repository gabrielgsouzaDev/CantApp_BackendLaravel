<?php

namespace App\Repositories;

use App\Models\ControleParental;

class ControleParentalRepository
{
    protected $model;

    public function __construct(ControleParental $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['responsavel', 'aluno', 'produtos'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['responsavel', 'aluno', 'produtos'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $controle = $this->model->find($id);
        if ($controle) {
            $controle->update($data);
            return $controle;
        }
        return null;
    }

    public function delete($id)
    {
        $controle = $this->model->find($id);
        if ($controle) {
            return $controle->delete();
        }
        return false;
    }
}
