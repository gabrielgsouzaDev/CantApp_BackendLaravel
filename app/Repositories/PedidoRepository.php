<?php

namespace App\Repositories;

use App\Models\Pedido;

class PedidoRepository
{
    protected $model;

    public function __construct(Pedido $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $pedido = $this->model->find($id);
        if ($pedido) {
            $pedido->update($data);
            return $pedido;
        }
        return null;
    }

    public function delete($id)
    {
        $pedido = $this->model->find($id);
        if ($pedido) {
            return $pedido->delete();
        }
        return false;
    }
}
