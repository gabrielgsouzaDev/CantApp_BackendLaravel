<?php

namespace App\Services;

use App\Repositories\PedidoRepository;
use App\Services\CarteiraService;
use App\Services\EstoqueService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
        // CRÍTICO R4: O PedidoRepository deve usar Eager Loading (Ex: with(['comprador', 'itens'])) aqui.
        return $this->repository->all();
    }

    public function find($id)
    {
        // CRÍTICO R7: O Controller trata a exceção 404. O Service só lança.
        return $this->repository->find($id);
    }

    public function getPedidosByComprador(string $userId)
    {
        // CRÍTICO R4: Garantir Eager Loading aqui.
        return $this->repository->getByComprador($userId);
    }

    public function getPedidosByCantina(string $cantinaId)
    {
        // CRÍTICO R4: Garantir Eager Loading aqui.
        return $this->repository->getByCantina($cantinaId);
    }

    /**
     * CRÍTICO R2/R8/R9/R10: Cria um pedido com itens, debita carteira do comprador e decrementa estoque.
     */
    public function createOrderWithItems(array $data)
    {
        $idComprador = $data['id_comprador'];
        $valorTotal = $data['valor_total'];
        
        // Validação de Pré-Transação (melhora UX, mas não é 100% seguro sem lock)
        // A validação de CONCORRÊNCIA é feita com lockForUpdate dentro dos Services!
        if (!$this->carteiraService->hasSufficientBalance($idComprador, $valorTotal)) {
             throw ValidationException::withMessages(['valor_total' => 'Saldo insuficiente na carteira.']);
        }
        foreach ($data['items'] as $item) {
            if (!$this->estoqueService->isStockAvailable($item['productId'], $item['quantity'])) {
                 throw ValidationException::withMessages(['items' => "Estoque insuficiente para o produto {$item['productId']}."]);
            }
        }

        // INÍCIO DA UNIDADE DE TRABALHO ATÔMICA (R2)
        return DB::transaction(function () use ($data, $idComprador, $valorTotal) {
            
            // 1. Debitar o valor da carteira do comprador
            // CRÍTICO R9: Este método DEVE aplicar lockForUpdate() na tabela 'carteiras'.
            $this->carteiraService->debit($idComprador, $valorTotal);

            // 2. Decrementar o estoque dos produtos
            foreach ($data['items'] as $item) {
                // CRÍTICO R8: Este método DEVE aplicar lockForUpdate() na tabela 'estoque'.
                $this->estoqueService->decrementStock($item['productId'], $item['quantity']);
            }

            // 3. Criar o pedido com os itens
            $pedido = $this->repository->createOrderWithItems($data);

            // 4. Implementar registro de transação formal (R10)
            $this->carteiraService->registerTransaction(
                $idComprador, 
                $valorTotal, 
                'DEBITO', // Tipo de transação
                "Pagamento Pedido #{$pedido->id}", 
                $pedido->id // Relaciona o registro de extrato com o ID do pedido
            ); 

            return $pedido;
        }); // Fim da Transação (commit ou rollback)
    }

    public function create(array $data)
    {
        // Este método deve ser descontinuado em favor de createOrderWithItems para manter a integridade.
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        // CRÍTICO R3: Este método genérico deve ser APENAS para Cantineiro (Policy).
        return $this->repository->update($id, $data);
    }

    /**
     * CRÍTICO R3/R10: Atualiza status do pedido, com lógica para reembolso e estorno de estoque se cancelado.
     */
    public function updateStatus($id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {
            // R3: Busca com lock para garantir que outro Cantineiro não altere o status simultaneamente
            $pedido = $this->repository->findWithLock($id); 
            $oldStatus = $pedido->status;
            $valorTotal = $pedido->valor_total;
            $idComprador = $pedido->id_comprador;
            
            // Validação de Transição de Status (State Machine Rules)
            $this->validateStatusTransition($oldStatus, $status);

            // 1. Atualiza o status
            $pedido = $this->repository->updateStatus($id, $status);

            // 2. Lógica de Estorno e Reembolso
            if ($status === 'cancelado' && $oldStatus !== 'cancelado') { 
                
                // CRÍTICO R9: Reembolsar valor (credit DEVE usar lockForUpdate())
                $this->carteiraService->credit($idComprador, $valorTotal);

                // CRÍTICO R8: Estornar estoque (incrementStock DEVE usar lockForUpdate())
                foreach ($pedido->itens as $item) {
                    $this->estoqueService->incrementStock($item->id_produto, $item->quantidade);
                }
                
                // CRÍTICO R10: Registro formal do reembolso
                $this->carteiraService->registerTransaction(
                    $idComprador, 
                    $valorTotal, 
                    'CREDITO', 
                    "Reembolso Pedido Cancelado #{$pedido->id}",
                    $pedido->id
                );
            }

            return $pedido;
        });
    }

    /**
     * @param string $oldStatus
     * @param string $newStatus
     * @throws Exception Se a transição for inválida (Regra de Negócio R3).
     */
    protected function validateStatusTransition(string $oldStatus, string $newStatus): void
    {
        $validTransitions = [
            'pendente' => ['confirmado', 'cancelado'],
            'confirmado' => ['em_preparo', 'cancelado'],
            'em_preparo' => ['pronto', 'cancelado'],
            'pronto' => ['entregue'],
            'entregue' => [], // Nenhuma transição permitida após entrega (Exceto por Super-Admin)
            'cancelado' => [], // Nenhuma transição permitida após cancelamento
        ];

        if (!isset($validTransitions[$oldStatus])) {
            throw new Exception("Status de pedido anterior inválido: {$oldStatus}.");
        }
        
        if (!in_array($newStatus, $validTransitions[$oldStatus])) {
             throw new Exception("Transição de status inválida: de '{$oldStatus}' para '{$newStatus}'.");
        }
    }


    public function delete($id)
    {
        // R3: Deve ser protegido por Policy/Admin.
        return $this->repository->delete($id);
    }
}