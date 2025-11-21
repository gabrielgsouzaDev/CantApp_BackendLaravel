<?php

namespace App\Repositories;

use App\Models\Pedido;

class PedidoRepository
{
    public function all()
    {
        return Pedido::all();
    }

    public function find(int $id): ?Pedido
    {
        return Pedido::find($id);
    }

    public function create(array $data): Pedido
    {
        return Pedido::create($data);
    }

    public function update(int $id, array $data): ?Pedido
    {
        $pedido = Pedido::find($id);
        if (!$pedido) return null;

        $pedido->update($data);
        return $pedido;
    }

    public function delete(int $id): bool
    {
        $pedido = Pedido::find($id);
        if (!$pedido) return false;

        return $pedido->delete();
    }

    /** Produtos do pedido */
    public function produtos(int $id)
    {
        $pedido = Pedido::find($id);
        return $pedido ? $pedido->produtos : null;
    }
}
