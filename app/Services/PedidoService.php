<?php

namespace App\Services;

use App\Repositories\PedidoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class PedidoService
{
    protected $repository;

    public function __construct(PedidoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public function getPedidosByComprador(string $userId)
    {
        return $this->repository->getByComprador($userId);
    }

    public function getPedidosByCantina(string $cantinaId)
    {
        return $this->repository->getByCantina($cantinaId);
    }

    /**
     * Cria um pedido com itens, preparado para futuras integrações:
     * - Controle Parental
     * - Débito na carteira
     * - Decremento de estoque
     * - API de pagamento (Pix/Cartão)
     */
    public function createOrderWithItems(array $data)
    {
        return DB::transaction(function () use ($data) {

            // TODO: Validar controle parental aqui
            // TODO: Integrar com carteira para débito do usuário
            // TODO: Checar estoque disponível e decrementar

            $pedido = $this->repository->createOrderWithItems($data);

            // TODO: Caso pagamento via Pix/Stripe seja aprovado, confirmar pedido

            return $pedido;
        });
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Atualiza status do pedido, preparado para futuras regras:
     * - Se cancelado: reembolsar carteira e estornar estoque
     */
    public function updateStatus($id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {

            $pedido = $this->repository->updateStatus($id, $status);

            if ($status === 'cancelado') {
                // TODO: Reembolsar valor para o comprador
                // TODO: Incrementar estoque dos itens
            }

            return $pedido;
        });
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
