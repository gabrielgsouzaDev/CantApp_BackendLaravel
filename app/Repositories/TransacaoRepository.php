<?php

namespace App\Repositories;

use App\Models\Transacao;

class TransacaoRepository
{
    protected $model;

    public function __construct(Transacao $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['carteira', 'autor', 'aprovador'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['carteira', 'autor', 'aprovador'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $transacao = $this->model->find($id);
        if ($transacao) {
            $transacao->update($data);
            return $transacao;
        }
        return null;
    }

    public function delete($id)
    {
        $transacao = $this->model->find($id);
        if ($transacao) {
            return $transacao->delete();
        }
        return false;
    }
}
