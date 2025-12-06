<?php

namespace App\Services;

use App\Repositories\EstoqueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException; // Adicionado para erros 422
use Exception;

class EstoqueService
{
    protected $repository;

    public function __construct(EstoqueRepository $repository)
    {
        $this->repository = $repository;
    }

    // ... (CRUD genérico)
    
    // Método para checagem rápida (UX) - Não seguro por si só, apenas para UX/Controller.
    public function isStockAvailable(int $productId, int $quantity): bool
    {
        $estoque = $this->repository->find($productId);
        return $estoque && $estoque->quantidade >= $quantity;
    }

    /**
     * Decrementa o estoque de um produto de forma segura.
     * CRÍTICO: Assume que a transação já foi iniciada pelo PedidoService.
     * @throws ValidationException Se o estoque for insuficiente (R8).
     */
    public function decrementStock(int $productId, int $quantity)
    {
        // CRÍTICO R8: Busca e Bloqueio da linha (lockForUpdate)
        // O Repository DEVE ter um método findByProductIdForUpdate que usa lockForUpdate()
        $estoque = $this->repository->findByProductIdForUpdate($productId); 
        
        if (!$estoque) {
            throw new Exception("Estoque do produto #{$productId} não encontrado.");
        }
        
        // Validação de Estoque dentro do LOCK
        if ($estoque->quantidade < $quantity) {
            // Lança ValidationException para ser tratada como 422 no Controller
            throw ValidationException::withMessages([
                'estoque' => "Estoque insuficiente para o produto #{$productId}. Disponível: {$estoque->quantidade}, Pedido: {$quantity}."
            ]);
        }
        
        $estoque->quantidade -= $quantity;
        $estoque->save();

        return $estoque;
    }

    /**
     * Incrementa o estoque de um produto de forma segura (para estornos/cancelamentos).
     * CRÍTICO: Assume que a transação já foi iniciada pelo PedidoService.
     */
    public function incrementStock(int $productId, int $quantity)
    {
        // CRÍTICO R8: Busca e Bloqueio da linha (lockForUpdate)
        $estoque = $this->repository->findByProductIdForUpdate($productId); 
        
        if (!$estoque) {
            throw new Exception("Estoque do produto #{$productId} não encontrado");
        }
        
        $estoque->quantidade += $quantity;
        $estoque->save();

        return $estoque;
    }
}