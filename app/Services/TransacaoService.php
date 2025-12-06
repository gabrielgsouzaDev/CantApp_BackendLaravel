<?php

namespace App\Services;

use App\Repositories\TransacaoRepository;

class TransacaoService
{
    protected $repository;

    public function __construct(TransacaoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        // R4: Adicionar Eager Loading with(['carteira', 'autor'])
        return $this->repository->all();
    }

    public function find($id)
    {
        // R4: Adicionar Eager Loading with(['carteira', 'autor'])
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        // R10: Este método DEVE ser chamado apenas pelo PedidoService ou CarteiraService (transacionalmente)
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * CRÍTICO R18: Método com nomenclatura correta e lógica de busca.
     */
    public function getTransactionsByUser(string $userId)
    {
        return $this->repository->model()
            // Busca transações onde o usuário é o AUTOR OU O APROVADOR
            ->where('id_user_autor', $userId) 
            ->orWhere('id_aprovador', $userId)
            ->with(['carteira', 'autor']) 
            ->orderBy('created_at', 'desc')
            ->get(); // Não usamos findOrFail para permitir listas vazias
    }

    /**
     * CRÍTICO R18: Método renomeado para convenção correta.
     */
    public function getTransactionsByCanteenId(string $cantinaId)
    {
        // R18/R7: Assumindo que o Repositório implementa a lógica complexa de busca (ex: JOIN com Pedidos/Cantina)
        return $this->repository->getByCanteenId($cantinaId);
    }
}