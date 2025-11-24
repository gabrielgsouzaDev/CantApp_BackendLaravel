<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class PedidoRepository
{
    protected $model;

    public function __construct(Pedido $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find($id)
    {
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
            ->findOrFail($id);
    }

    public function getByComprador(string $userId)
    {
        return $this->model->where('id_comprador', $userId)
            ->orWhere('id_destinatario', $userId)
            ->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByCantina(string $cantinaId)
    {
        return $this->model->where('id_cantina', $cantinaId)
            ->with(['comprador', 'destinatario', 'itens.produto'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createOrderWithItems(array $data)
    {
        return DB::transaction(function () use ($data) {

            $itemsData = $data['items'];
            unset($data['items']);

            $pedido = $this->model->create($data);

            $itensToCreate = collect($itemsData)->map(function ($item) {
                return [
                    'id_produto' => $item['productId'],
                    'quantidade' => $item['quantity'],
                    'valor_unitario' => $item['unitPrice'],
                ];
            })->all();

            $pedido->itens()->createMany($itensToCreate);

            return $pedido->load(['itens.produto']);
        });
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStatus($id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $pedido = $this->model->findOrFail($id);
            $pedido->status = $status;
            $pedido->save();

            return $pedido;
        });
    }

    public function update($id, array $data)
    {
        $pedido = $this->model->find($id);
        if ($pedido) {
            $pedido->update($data);
            return $pedido->fresh();
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
