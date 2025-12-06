<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// CRÍTICO R7: Removida a injeção de Illuminate\Support\Facades\DB, pois o Service é quem inicia a transação.

class PedidoRepository
{
    protected $model;

    public function __construct(Pedido $model)
    {
        $this->model = $model;
    }

    // --- Métodos de Leitura Padrão (Eager Loading) ---

    /**
     * @return Collection|Pedido[]
     */
    public function all()
    {
        // R4: Eager Loading para evitar N+1
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->orderBy('created_at', 'desc')
                           ->get();
    }

    /**
     * @throws ModelNotFoundException
     * @return Pedido
     */
    public function find($id)
    {
        // R4: Eager Loading
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->findOrFail($id);
    }
    
    // CRÍTICO R9/R8: Método de busca com Lock Pessimista para uso no PedidoService (fluxo de estorno)
    public function findWithLock($id)
    {
        // Busca o pedido, incluindo os itens (necessário para o estorno de estoque)
        return $this->model->with(['itens'])
                           ->where($this->model->getKeyName(), $id) // Usa getKeyName() para compatibilidade (ex: 'id_pedido')
                           ->lockForUpdate() // CRÍTICO: Aplica o Lock
                           ->firstOrFail();
    }

    public function getByComprador(string $userId)
    {
        // R4: Eager Loading
        return $this->model->where('id_comprador', $userId)
                           ->orWhere('id_destinatario', $userId)
                           ->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->orderBy('created_at', 'desc')
                           ->get();
    }
    
    public function getByCantina(string $cantinaId)
    {
        // R4: Otimização: Eager Loading sem o relacionamento 'cantina'
        return $this->model->where('id_cantina', $cantinaId)
                           ->with(['comprador', 'destinatario', 'itens.produto'])
                           ->orderBy('created_at', 'desc')
                           ->get();
    }

    // --- Métodos de Escrita ---

    /**
     * CRÍTICO R7: Criação do Pedido e Itens. Removido DB::transaction aninhado.
     * Assume que o Service já iniciou a transação.
     * @return Pedido
     */
    public function createOrderWithItems(array $data)
    {
        $itemsData = $data['items'];
        unset($data['items']); 

        // 1. Cria o Pedido principal
        $pedido = $this->model->create($data);

        // 2. Formata e cria os Itens do Pedido (R7)
        $itensToCreate = collect($itemsData)->map(function ($item) {
            return [
                'id_produto' => $item['productId'],
                'quantidade' => $item['quantity'],
                'preco_unitario' => $item['unitPrice'], // CRÍTICO: Nome de coluna real
            ];
        })->all();
        
        $pedido->itens()->createMany($itensToCreate);

        // Retorna o Pedido completo com os itens
        return $pedido->load(['itens.produto']);
    }
    
    public function create(array $data)
    {
        // R3: Assume $fillable seguro no Model
        return $this->model->create($data);
    }
    
    /**
     * CRÍTICO R7: Atualiza o status de um pedido. Removido DB::transaction aninhado.
     * Deve ser chamado DENTRO de uma transação superior.
     * @return Pedido
     */
    public function updateStatus($id, string $status)
    {
        $pedido = $this->model->findOrFail($id);
        $pedido->status = $status;
        $pedido->save();
        return $pedido; 
    }

    public function update($id, array $data)
    {
        // R3: Assume que o Controller enviará APENAS campos seguros (fillable/guarded).
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
            // CRÍTICO R5: Excluir itens primeiro (se 'onDelete(cascade)' não estiver na migration)
            $pedido->itens()->delete();
            return $pedido->delete();
        }
        return false;
    }
}