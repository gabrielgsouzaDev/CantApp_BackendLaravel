<?php

namespace App\Repositories;

use App\Models\ItemPedido;

class ItemPedidoRepository
{
    public function all()
    {
        return ItemPedido::all();
    }

    public function find(int $id): ?ItemPedido
    {
        return ItemPedido::find($id);
    }

    public function create(array $data): ItemPedido
    {
        return ItemPedido::create($data);
    }

    public function update(int $id, array $data): ?ItemPedido
    {
        $item = ItemPedido::find($id);
        if (!$item) return null;

        $item->update($data);
        return $item;
    }

    public function delete(int $id): bool
    {
        $item = ItemPedido::find($id);
        if (!$item) return false;

        return $item->delete();
    }
}
