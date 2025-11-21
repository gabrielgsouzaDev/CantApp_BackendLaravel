<?php

namespace App\Repositories;

use App\Models\Endereco;

class EnderecoRepository
{
    protected $model;

    public function __construct(Endereco $model)
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
        $endereco = $this->model->find($id);
        if ($endereco) {
            $endereco->update($data);
            return $endereco;
        }
        return null;
    }

    public function delete($id)
    {
        $endereco = $this->model->find($id);
        if ($endereco) {
            return $endereco->delete();
        }
        return false;
    }
}
