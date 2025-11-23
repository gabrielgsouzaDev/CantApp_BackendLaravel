<?php

namespace App\Repositories;

use App\Models\Cantina;

class CantinaRepository
{
    protected $model;

    public function __construct(Cantina $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model
            ->with(['escola', 'produtos', 'pedidos', 'usuarios'])
            ->get();
    }

    public function find($id)
    {
        return $this->model
            ->with(['escola', 'produtos', 'pedidos', 'usuarios'])
            ->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $cantina = $this->model->find($id);

        if ($cantina) {
            $cantina->update($data);
            return $cantina;
        }

        return null;
    }

    public function delete($id)
    {
        $cantina = $this->model->find($id);

        if ($cantina) {
            return $cantina->delete();
        }

        return false;
    }

    // ✅ ADICIONADO — rota /cantinas/escola/{id_escola}
    public function getBySchool($id_escola)
    {
        return $this->model
            ->where('id_escola', $id_escola)
            ->with(['produtos'])
            ->get();
    }
}
