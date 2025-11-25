<?php

namespace App\Repositories;

use App\Models\Pedido;
use Illuminate\Support\Facades\DB; // Necessário para transações

class PedidoRepository
{
    protected $model;

    public function __construct(Pedido $model)
    {
        $this->model = $model;
    }

    // --- Métodos de Leitura Padrão ---

    public function all()
    {
        // Garante que todas as relações importantes sejam carregadas
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->orderBy('created_at', 'desc')
                           ->get();
    }

    public function find($id)
    {
        // Garante que todas as relações importantes sejam carregadas
        return $this->model->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->findOrFail($id); // Usamos findOrFail para lançar 404 se não encontrado
    }

    // --- Métodos de Leitura Específicos ---
    
    // NOVO: Busca pedidos por ID do Comprador ou Destinatário (para Aluno/Pai)
    public function getByComprador(string $userId)
    {
        return $this->model->where('id_comprador', $userId)
                           ->orWhere('id_destinatario', $userId)
                           ->with(['cantina', 'comprador', 'destinatario', 'itens.produto'])
                           ->orderBy('created_at', 'desc')
                           ->get();
    }
    
    // NOVO: Busca pedidos por ID da Cantina (para Cantineiro)
    public function getByCantina(string $cantinaId)
    {
        return $this->model->where('id_cantina', $cantinaId)
                           ->with(['comprador', 'destinatario', 'itens.produto']) // Não precisa de cantina aqui, já sabemos qual é
                           ->orderBy('created_at', 'desc')
                           ->get();
    }

    // --- Métodos de Escrita Padrão ---

    // CORRIGIDO/EXPANDIDO: Agora lida com a criação do Pedido e dos Itens do Pedido em uma Transação
    public function createOrderWithItems(array $data)
    {
        // Garante que tanto o Pedido quanto seus Itens sejam criados ou nenhum seja.
        return DB::transaction(function () use ($data) {
            
            $itemsData = $data['items'];
            unset($data['items']); // Remove os itens antes de criar o Pedido principal

            // 1. Cria o Pedido principal
            $pedido = $this->model->create($data);

            // 2. Cria os Itens do Pedido (usando o relacionamento 'itens' definido no Model Pedido)
            $itensToCreate = collect($itemsData)->map(function ($item) {
                return [
                    'id_produto' => $item['productId'],
                    'quantidade' => $item['quantity'],
                    // CORREÇÃO CRÍTICA (422 FIX): Usando 'preco_unitario' (coluna real do DB)
                    'preco_unitario' => $item['unitPrice'], 
                ];
            })->all();
            
            $pedido->itens()->createMany($itensToCreate);

            // Retorna o Pedido completo com os itens
            return $pedido->load(['itens.produto']);
        });
    }

    // Método Padrão de Criação (mantido, mas pode ser desnecessário se createOrderWithItems for o único usado)
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    // NOVO: Método dedicado para atualização de status
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
            // Retorna o objeto atualizado
            return $pedido->fresh(); 
        }
        return null;
    }

    public function delete($id)
    {
        $pedido = $this->model->find($id);
        if ($pedido) {
            // 🚨 IMPORTANTE: Se o PedidoModel não tiver 'cascade on delete',
            // você deve deletar os itens do pedido primeiro aqui.
            // $pedido->itens()->delete();
            return $pedido->delete();
        }
        return false;
    }
}