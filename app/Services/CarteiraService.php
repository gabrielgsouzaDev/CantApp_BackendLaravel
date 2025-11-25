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
        // O CarteiraRepository é injetado, permitindo acesso aos métodos do Eloquent com segurança.
        $this->repository = $repository;
    }

    // (Métodos CRUD genéricos como all, find, create, update, delete estariam aqui, mas omitidos para foco)

    /**
     * Processa um débito na carteira do usuário (Ex: Pagamento de Pedido).
     * Garante a atomicidade via transação e bloqueio de linha (lockForUpdate).
     *
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser debitado.
     * @param string $descricao Descrição da transação.
     * @return \App\Models\Carteira Carteira atualizada.
     * @throws SaldoInsuficienteException Se o saldo for menor que o valor.
     * @throws Exception Se a carteira não for encontrada.
     */
    public function debit(int $userId, float $amount, string $descricao = 'Débito'): \App\Models\Carteira
    {
        return DB::transaction(function () use ($userId, $amount, $descricao) {
            
            // 1. Busca e Bloqueio da linha (lockForUpdate) para evitar concorrência
            $carteira = $this->repository->findByUserIdForUpdate($userId);
            
            if (!$carteira) {
                throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
            }
            
            // 2. Verifica Saldo
            if ($carteira->saldo < $amount) {
                throw new SaldoInsuficienteException();
            }
            
            // 3. Executa o Débito
            $carteira->saldo -= $amount;
            $this->repository->save($carteira);

            // 4. Registro da Transação
            Transacao::create([
                'id_carteira' => $carteira->id_carteira, 
                'id_user_autor' => $userId, 
                'id_aprovador' => null,
                'uuid' => (string) Str::uuid(),
                'tipo' => 'DEBITO',
                'valor' => $amount,
                'descricao' => $descricao,
                'referencia' => null,
                // CORRIGIDO: O status deve ser 'concluida' para bater com o ENUM do banco de dados.
                'status' => 'concluida', 
            ]);

            return $carteira;
        });
    }

    /**
     * Processa um crédito na carteira do usuário (Ex: Recarga).
     * Garante a atomicidade via transação e bloqueio de linha (lockForUpdate).
     *
     * @param int $userId ID do usuário.
     * @param float $amount Valor a ser creditado.
     * @param string $descricao Descrição da transação.
     * @return \App\Models\Carteira Carteira atualizada.
     * @throws Exception Se a carteira não for encontrada.
     */
    public function credit(int $userId, float $amount, string $descricao = 'Recarga'): \App\Models\Carteira
    {
        return DB::transaction(function () use ($userId, $amount, $descricao) {
            
            // 1. Busca e Bloqueio da linha (lockForUpdate)
            $carteira = $this->repository->findByUserIdForUpdate($userId);
            
            if (!$carteira) {
                throw new Exception("Carteira do usuário não encontrada (ID: {$userId}).");
            }

            // 2. Executa o Crédito
            $carteira->saldo += $amount;
            $this->repository->save($carteira);

            // 3. Registro da Transação
            Transacao::create([
                'id_carteira' => $carteira->id_carteira, // Usando a PK 'id_carteira'
                'id_user_autor' => $userId, 
                'id_aprovador' => null, 
                'uuid' => (string) Str::uuid(),
                // NOTA: O 'tipo' para recarga pode ser 'Recarregar' ou 'PIX' dependendo da origem.
                // Mantendo 'CREDITO' por enquanto.
                'tipo' => 'CREDITO', 
                'valor' => $amount,
                'descricao' => $descricao,
                'referencia' => null,
                // CORRIGIDO: O status deve ser 'concluida' para bater com o ENUM do banco de dados.
                'status' => 'concluida', 
            ]);

            return $carteira;
        });
    }
}