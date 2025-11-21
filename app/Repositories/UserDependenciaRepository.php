<?php

namespace App\Repositories;

use App\Models\UserDependencia;

class UserDependenciaRepository
{
    protected $model;

    public function __construct(UserDependencia $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['responsavel', 'dependente'])->get();
    }

    public function find($responsavelId, $dependenteId)
    {
        return $this->model->with(['responsavel', 'dependente'])
            ->where('id_responsavel', $responsavelId)
            ->where('id_dependente', $dependenteId)
            ->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function delete($responsavelId, $dependenteId)
    {
        $dependencia = $this->find($responsavelId, $dependenteId);
        if ($dependencia) {
            return $dependencia->delete();
        }
        return false;
    }
}
