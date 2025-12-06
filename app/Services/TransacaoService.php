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
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
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

    public function getTransactionsByUser(string $userId)
{
    // CRÍTICO: Assume-se que o erro está aqui, onde a busca falha.
    // O Service DEVE buscar transações onde o usuário é o AUTOR.
    
    return $this->repository->model()
        // O relacionamento 'autor' ou o campo 'id_user_autor' deve ser usado
        ->where('id_user_autor', $userId) 
        // Adicionamos um OR para buscar transações onde ele é o aprovador, se necessário
        ->orWhere('id_aprovador', $userId)
        ->with(['carteira', 'autor']) // Eager Loading R4
        ->orderBy('created_at', 'desc')
        ->get(); // Usar get() em vez de findOrFail() para evitar erro 404/400 se não houver transações.
}

    // Busca transações relacionadas a uma cantina específica
    public function getTransacoesByCanteenId(string $cantinaId)
    {
        return $this->repository->getByCanteenId($cantinaId);
    }
}
