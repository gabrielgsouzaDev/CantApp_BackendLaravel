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
     * CRÍTICO R36: Usa comparação por centavos (inteiros) para precisão.
     */
    public function hasSufficientBalance(int $userId, float $amount): bool
    {
        $carteira = $this->repository->findByUserId($userId); 
        
        if (!$carteira) {
            return false;
        }

        // Converte valores para centavos (inteiros) para comparação segura
        $saldoCentavos = (int) round($carteira->saldo * 100);
        $amountCentavos = (int) round($amount * 100);
        
        return $saldoCentavos >= $amountCentavos;
    }

    /**
     * Processa um débito na carteira do usuário de forma ATÔMICA. 
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
            // CRÍTICO R36: Checagem por centavos
            $saldoCentavos = (int) round($carteira->saldo * 100);
            $amountCentavos = (int) round($amount * 100);
            
            if ($saldoCentavos < $amountCentavos) { 
                throw new SaldoInsuficienteException("Saldo insuficiente na carteira para o débito.");
            }
            
            // 3. Executa o Débito
            // CRÍTICO R36: Executa a subtração e formata de volta para float/decimal (mantendo precisão)
            $novoSaldoCentavos = $saldoCentavos - $amountCentavos;
            $carteira->saldo = $novoSaldoCentavos / 100.00; // Salva como float (o Model cuida do cast para DB)

            $this->repository->save($carteira);

            // 4. Registro da Transação (R10)
            $transacao = Transacao::create([
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

            return [$carteira, $transacao]; 
        }); 
    }

    /**
     * Processa um crédito na carteira do usuário de forma ATÔMICA. 
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
            // CRÍTICO R36: Usa aritmética de inteiros (centavos) para precisão.
            $saldoCentavos = (int) round($carteira->saldo * 100);
            $amountCentavos = (int) round($amount * 100);
            
            $novoSaldoCentavos = $saldoCentavos + $amountCentavos;
            $carteira->saldo = $novoSaldoCentavos / 100.00; // Salva como float
            
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

            return [$carteira, $transacao];
        });
    }
}