<?php

namespace App\Services;

use App\Repositories\CarteiraRepository;
use App\Models\Transacao;
use App\Exceptions\SaldoInsuficienteException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CarteiraService
{
    protected $repository;

    public function __construct(CarteiraRepository $repository)
    {
        $this->repository = $repository;
    }
    
    // Método para checagem rápida (UX) - Não seguro por si só, apenas para UX/Controller.
    public function hasSufficientBalance(int $userId, float $amount): bool
    {
        $carteira = $this->repository->findByUserId($userId); 
        return $carteira && $carteira->saldo >= $amount;
    }

    /**
     * Processa um débito na carteira do usuário. 
     * CRÍTICO: Assume que a transação já foi iniciada pelo PedidoService.
     *
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser debitado.
     * @param string $descricao Descrição da transação.
     * @param int|null $referenciaId ID do Pedido ou outra referência (R10).
     * @throws SaldoInsuficienteException Se o saldo for menor que o valor (R9).
     */
    public function debit(int $userId, float $amount, string $descricao = 'Pagamento de Pedido', ?int $referenciaId = null): \App\Models\Carteira
    {
        // 1. Busca e Bloqueio da linha (lockForUpdate) - CRÍTICO R9
        $carteira = $this->repository->findByUserIdForUpdate($userId); // O Repository DEVE ter o lockForUpdate()
        
        if (!$carteira) {
            throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
        }
        
        // 2. Verifica Saldo DENTRO do lock
        if ($carteira->saldo < $amount) {
            // Lança exceção específica para facilitar o tratamento
            throw new SaldoInsuficienteException("Saldo insuficiente na carteira para o débito de R$ " . number_format($amount, 2, ',', '.'));
        }
        
        // 3. Executa o Débito
        $carteira->saldo -= $amount;
        $this->repository->save($carteira);

        // 4. Registro da Transação (R10)
        Transacao::create([
            'id_carteira' => $carteira->id_carteira, 
            'id_user_autor' => $userId, 
            'id_aprovador' => null,
            'uuid' => (string) Str::uuid(),
            'tipo' => 'DEBITO',
            'valor' => $amount,
            'descricao' => $descricao,
            'referencia' => $referenciaId, // Vínculo ao Pedido
            'status' => 'concluida', 
        ]);

        return $carteira;
    }

    /**
     * Processa um crédito na carteira do usuário. 
     * CRÍTICO: Assume que a transação já foi iniciada pelo PedidoService.
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser creditado.
     * @param string $descricao Descrição da transação.
     * @param int|null $referenciaId ID do Pedido ou outra referência (R10).
     */
    public function credit(int $userId, float $amount, string $descricao = 'Recarga/Estorno', ?int $referenciaId = null): \App\Models\Carteira
    {
        // 1. Busca e Bloqueio da linha (lockForUpdate) - CRÍTICO R9
        $carteira = $this->repository->findByUserIdForUpdate($userId); // O Repository DEVE ter o lockForUpdate()
        
        if (!$carteira) {
            throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
        }

        // 2. Executa o Crédito
        $carteira->saldo += $amount;
        $this->repository->save($carteira);

        // 3. Registro da Transação (R10)
        Transacao::create([
            'id_carteira' => $carteira->id_carteira, 
            'id_user_autor' => $userId, 
            'id_aprovador' => null, 
            'uuid' => (string) Str::uuid(),
            'tipo' => 'CREDITO', 
            'valor' => $amount,
            'descricao' => $descricao,
            'referencia' => $referenciaId, // Vínculo ao Pedido (Ex: Estorno)
            'status' => 'concluida', 
        ]);

        return $carteira;
    }
}