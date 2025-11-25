<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use Exception; // Incluído para tratamento de erro

class ProdutoService
{
    protected $repository;

    public function __construct(ProdutoRepository $repository)
    {
        $this->repository = $repository;
    }

    // --- MÉTODOS CRUD PADRÃO ---
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
    
    // --- MÉTODO DE NEGÓCIO (CORREÇÃO PARA O ERRO 500) ---
    /**
     * Busca os produtos disponíveis em uma cantina específica.
     * Implementa o método ausente que estava causando o 500.
     * * @param int $cantinaId ID da cantina.
     * @return \Illuminate\Database\Eloquent\Collection Coleção de produtos.
     */
    public function getProdutosByCantina(int $cantinaId)
    {
        try {
            // Delega a lógica de busca ao ProdutoRepository.
            // O repositório deve ter o método 'getByCantina' implementado.
            return $this->repository->getByCantina($cantinaId);
        } catch (Exception $e) {
            // Registra o erro interno e lança uma exceção para o Controller
            // O Controller irá então retornar um 500 detalhado.
            throw new Exception("Falha ao buscar produtos da cantina ID {$cantinaId}: " . $e->getMessage());
        }
    }
}