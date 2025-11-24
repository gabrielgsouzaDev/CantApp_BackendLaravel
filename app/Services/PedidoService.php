<?php

namespace App\Services;

use App\Repositories\PedidoRepository;
use App\Services\CarteiraService;
use App\Services\EstoqueService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class PedidoService
{
    protected $repository;
    protected $carteiraService;
    protected $estoqueService;

    public function __construct(
        PedidoRepository $repository,
        CarteiraService $carteiraService,
        EstoqueService $estoqueService
    ) {
        $this->repository = $repository;
        $this->carteiraService = $carteiraService;
        $this->estoqueService = $estoqueService;
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
     * Cria um pedido com itens, debita carteira do comprador e decrementa estoque.
     */
    public function createOrderWithItems(array $data)
    {
        return DB::transaction(function () use ($data) {
            $idComprador = $data['id_comprador'];
            $valorTotal = $data['valor_total'];

            // 1. Debitar o valor da carteira do comprador
            $this->carteiraService->debit($idComprador, $valorTotal);

            // 2. Decrementar o estoque dos produtos
            foreach ($data['items'] as $item) {
                $this->estoqueService->decrementStock($item['productId'], $item['quantity']);
            }

            // 3. Criar o pedido com os itens
            $pedido = $this->repository->createOrderWithItems($data);

            // 4. (Opcional) Criar transações de débito no extrato
            // TODO: Implementar registro de transação formal

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
     * Atualiza status do pedido, com lógica para reembolso e estorno de estoque se cancelado
     */
    public function updateStatus($id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $pedido = $this->repository->updateStatus($id, $status);

            if ($status === 'cancelado') {
                // Reembolsar valor para o comprador
                $this->carteiraService->credit($pedido->id_comprador, $pedido->valor_total);

                // Estornar estoque dos itens
                foreach ($pedido->itens as $item) {
                    $this->estoqueService->incrementStock($item->id_produto, $item->quantidade);
                }
            }

            return $pedido;
        });
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
