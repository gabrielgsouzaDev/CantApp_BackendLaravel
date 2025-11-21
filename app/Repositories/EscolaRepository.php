<?php

namespace App\Repositories;

use App\Models\Escola;

class EscolaRepository
{
    protected $model;

    public function __construct(Escola $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['endereco', 'plano', 'cantinas', 'alunos'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['endereco', 'plano', 'cantinas', 'alunos'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $escola = $this->model->find($id);
        if ($escola) {
            $escola->update($data);
            return $escola;
        }
        return null;
    }

    public function delete($id)
    {
        $escola = $this->model->find($id);
        if ($escola) {
            return $escola->delete();
        }
        return false;
    }
}
