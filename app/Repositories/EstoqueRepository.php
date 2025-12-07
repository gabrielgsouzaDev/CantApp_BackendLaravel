<?php

namespace App\Repositories;

use App\Models\Estoque;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Log;

class EstoqueRepository
{
    protected $model;

    public function __construct(Estoque $model)
    {
        $this->model = $model;
    }

    // --- Métodos CRUD Padrão ---

    public function all()
    {
        return $this->model->with('produto')->get();
    }

    // CRÍTICO: find() busca pela chave primária (id_estoque).
    // Usaremos findByProductId para lógica de negócio.
    public function find($id)
    {
        return $this->model->with('produto')->find($id);
    }
    
    // Método de busca pelo ID do produto (usado para UX/checagem rápida)
    public function findByProductId(int $productId): ?Estoque
    {
        return $this->model->where('id_produto', $productId)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $estoque = $this->model->find($id);
        if ($estoque) {
            $estoque->update($data);
            return $estoque;
        }
        return null;
    }

    public function delete($id)
    {
        $estoque = $this->model->find($id);
        if ($estoque) {
            return $estoque->delete();
        }
        return false;
    }

    // --- Métodos de Concorrência e Transação (R8) ---

    /**
     * Busca o estoque de um produto específica para atualização (lockForUpdate).
     * CRÍTICO R40: Este método estava ausente, causando o Erro 500 no PedidoService.
     * Deve ser chamado DENTRO de uma transação superior.
     * @param int $productId
     * @return \App\Models\Estoque
     * @throws Exception
     */
    public function findByProductIdForUpdate(int $productId): Estoque
    {
        try {
             // R8: Busca pelo id_produto e aplica o Bloqueio Pessimista na linha.
             return $this->model
                         ->where('id_produto', $productId)
                         ->lockForUpdate() 
                         ->firstOrFail(); 
        } catch (ModelNotFoundException $e) {
             Log::error("Estoque não encontrado para o produto: {$productId}");
             // Lança uma exceção para o Service tratar (reverter a transação)
             throw new Exception("Estoque do produto (ID: {$productId}) não encontrado para bloqueio.");
        }
    }

    /**
     * CRÍTICO: Métodos de decremento e incremento foram movidos para o Service (R8)
     * e o lockForUpdate foi movido para findByProductIdForUpdate (R40).
     * * MANTENDO OS MÉTODOS ANTIGOS PARA REFERÊNCIA (REMOVENDO O DB::transaction INCORRETO):
     */

    /**
     * @deprecated Use EstoqueService::decrementStock().
     */
    public function decrementStock(int $productId, int $quantity)
    {
        // O Service que deve chamar findByProductIdForUpdate e save.
        // Este método não deve mais ser usado ou deve ser reescrito para fins de concorrência.
        throw new Exception("Chamada incorreta: O Service deve gerenciar o decremento e o lock.");
    }

    /**
     * @deprecated Use EstoqueService::incrementStock().
     */
    public function incrementStock(int $productId, int $quantity)
    {
        // O Service que deve chamar findByProductIdForUpdate e save.
        // Este método não deve mais ser usado ou deve ser reescrito para fins de concorrência.
        throw new Exception("Chamada incorreta: O Service deve gerenciar o incremento e o lock.");
    }
}