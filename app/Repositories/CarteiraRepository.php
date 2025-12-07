<?php

namespace App\Repositories;

use App\Models\Carteira;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException; // Adicionado para defesa extra
use Exception;
use Log; // Adicionado para logging em caso de falha de query

class CarteiraRepository
{
    protected $model;

    public function __construct(Carteira $model)
    {
        $this->model = $model;
    }
    
    // --- Métodos de Consulta ---

    public function findByUserId(int $userId): ?Carteira
    {
        return $this->model->where('id_user', $userId)->first();
    }
    
    /**
     * Busca a carteira de um usuário específica para atualização (lockForUpdate).
     * Essencial para evitar condições de corrida (R9) e DEVE ser chamado dentro de DB::transaction.
     *
     * @param int $userId
     * @return \App\Models\Carteira|null
     * @throws Exception
     */
    public function findByUserIdForUpdate(int $userId): ?Carteira
    {
        try {
             // CRÍTICO R9/R23: Combina a busca e o bloqueio em UMA única consulta.
             return $this->model
                         ->where('id_user', $userId)
                         ->lockForUpdate() // Aplica o bloqueio pessimista na busca
                         ->firstOrFail(); // Lança ModelNotFoundException
                         
        } catch (ModelNotFoundException $e) {
            // R25: Trata a exceção de registro não encontrado
            throw new Exception("Carteira do usuário (ID: {$userId}) não encontrada.", 0, $e);
            
        } catch (QueryException $e) {
            // R25: TRATAMENTO CRÍTICO: Captura falhas de SQL (tabela/coluna errada)
            Log::error("QueryException no Repositório (findByUserIdForUpdate): " . $e->getMessage());
            throw new Exception("Falha de comunicação com o banco de dados durante o bloqueio da carteira.", 0, $e);

        } catch (Exception $e) {
             // R25: Captura qualquer outra falha genérica
             Log::error("Erro Desconhecido no Repositório: " . $e->getMessage());
             throw new Exception("Erro interno inesperado ao buscar carteira.", 0, $e);
        }
    }
    
    // --- Métodos de Manipulação ---
    
    public function create(array $data): Carteira
    {
        return $this->model->create($data);
    }

    /**
     * Salva ou atualiza uma instância de Carteira (usado para salvar o novo saldo).
     *
     * @param \App\Models\Carteira $carteira
     * @return bool
     */
    public function save(Carteira $carteira): bool
    {
        // R25: Esta operação também deve ser defensiva, pois um erro aqui causa o rollback
        try {
            return $carteira->save();
        } catch (QueryException $e) {
            Log::error("QueryException no Repositório (save): " . $e->getMessage());
            throw new Exception("Falha ao salvar o novo saldo da carteira.", 0, $e);
        }
    }
}