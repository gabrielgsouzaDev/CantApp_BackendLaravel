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
    
    /**
     * Método para checagem rápida (UX).
     * CRÍTICO R36: Implementa bccomp para evitar erros de ponto flutuante.
     */
    public function hasSufficientBalance(int $userId, float $amount): bool
    {
        $carteira = $this->repository->findByUserId($userId); 
        
        // Retorna TRUE se o saldo for maior ou igual (>= 0) ao valor requerido.
        return $carteira && bccomp($carteira->saldo, $amount, 2) >= 0;
    }

    /**
     * Processa um débito na carteira do usuário de forma ATÔMICA. 
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser debitado.
     * @param string $descricao Descrição da transação.
     * @param string|null $referenciaId ID do Pedido ou outra referência (R10).
     * @throws SaldoInsuficienteException Se o saldo for menor que o valor (R9).
     */
    public function debit(int $userId, float $amount, string $descricao = 'Pagamento de Pedido', ?string $referenciaId = null): array
    {
        // CRÍTICO R22: Inicia a Transação Atômica
        return DB::transaction(function () use ($userId, $amount, $descricao, $referenciaId) {

            // 1. Busca e Bloqueio da linha (lockForUpdate)
            $carteira = $this->repository->findByUserIdForUpdate($userId); 
            
            if (!$carteira) {
                throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
            }
            
            // 2. Verifica Saldo DENTRO do lock
            // CRÍTICO R36: Usa bccomp para checagem precisa (retorna -1 se saldo < amount).
            if (bccomp($carteira->saldo, $amount, 2) === -1) { 
                throw new SaldoInsuficienteException("Saldo insuficiente na carteira para o débito.");
            }
            
            // 3. Executa o Débito
            // CRÍTICO R36: Usa bcsub para subtrair com precisão de 2 casas decimais.
            $carteira->saldo = bcsub($carteira->saldo, $amount, 2);
            $this->repository->save($carteira);

            // 4. Registro da Transação (R10)
            $transacao = Transacao::create([ // CRÍTICO: Capture a Transação
                'id_carteira' => (int) $carteira->id_carteira, 
                'id_user_autor' => $userId > 0 ? (int) $userId : null, 
                'id_aprovador' => $userId > 0 ? (int) $userId : null, 
                'uuid' => (string) Str::uuid(),
                'tipo' => 'Debito', 
                'valor' => $amount,
                'descricao' => $descricao,
                'referencia' => $referenciaId, 
                'status' => 'concluida', 
            ]);

            return [$carteira, $transacao]; // CRÍTICO R31: Retorna ambos os objetos
        }); 
    }

    /**
     * Processa um crédito na carteira do usuário de forma ATÔMICA. 
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser creditado.
     * @param string $descricao Descrição da transação.
     * @param string|null $referenciaId ID do Pedido ou outra referência (R10).
     * @return array [Carteira, Transacao]
     */
    public function credit(int $userId, float $amount, string $descricao = 'Recarga/Estorno', ?string $referenciaId = null): array
    {
        // CRÍTICO R22: Inicia a Transação Atômica
        return DB::transaction(function () use ($userId, $amount, $descricao, $referenciaId) {
            
            // 1. Busca e Bloqueio da linha (lockForUpdate)
            $carteira = $this->repository->findByUserIdForUpdate($userId); 
            
            if (!$carteira) {
                throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
            }

            // 2. Executa o Crédito
            // CRÍTICO R36: Usa bcadd para somar com precisão de 2 casas decimais.
            $carteira->saldo = bcadd($carteira->saldo, $amount, 2);
            $this->repository->save($carteira);

            // 3. Registro da Transação (R10)
            $transacao = Transacao::create([ 
                'id_carteira' => (int) $carteira->id_carteira, 
                'id_user_autor' => $userId > 0 ? (int) $userId : null, 
                'id_aprovador' => $userId > 0 ? (int) $userId : null, 
                'uuid' => (string) Str::uuid(),
                'tipo' => 'Recarregar', 
                'valor' => $amount,
                'descricao' => $descricao,
                'referencia' => $referenciaId, 
                'status' => 'confirmada', 
            ]);

            return [$carteira, $transacao]; // CRÍTICO R31: Retorna ambos os objetos
        });
    }
}