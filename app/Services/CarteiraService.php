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
            if ($carteira->saldo < $amount) {
                throw new SaldoInsuficienteException("Saldo insuficiente na carteira para o débito.");
            }
            
            // 3. Executa o Débito
            $carteira->saldo -= $amount;
            $this->repository->save($carteira);

            // 4. Registro da Transação (R10)
            $transacao = Transacao::create([ // CRÍTICO: Capture a Transação
                'id_carteira' => (int) $carteira->id_carteira, 
                'id_user_autor' => $userId > 0 ? (int) $userId : null, 
                'id_aprovador' => $userId > 0 ? (int) $userId : null, 
                'uuid' => (string) Str::uuid(),
                'tipo' => 'Debito', // Alinhado com o ENUM da Migration
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
            $carteira->saldo += $amount;
            $this->repository->save($carteira);

            // 3. Registro da Transação (R10)
            $transacao = Transacao::create([ // CRÍTICO: Capture a Transação
                // R24: Garantia de Cast para INT
                'id_carteira' => (int) $carteira->id_carteira, 
                'id_user_autor' => $userId > 0 ? (int) $userId : null, 
                'id_aprovador' => $userId > 0 ? (int) $userId : null, 
                'uuid' => (string) Str::uuid(),
                
                // CRÍTICO R28: Alinha o valor com o ENUM da Migration
                'tipo' => 'Recarregar', 
                
                'valor' => $amount,
                'descricao' => $descricao,
                'referencia' => $referenciaId, 
                'status' => 'confirmada', // Status ajustado para 'confirmada' conforme ENUM
            ]);

            return [$carteira, $transacao]; // CRÍTICO R31: Retorna ambos os objetos
        });
    }
    
    // NOTA: Para que este Service funcione, os métodos findByUserIdForUpdate e save
    // DEVE existir no seu CarteiraRepository.
}