<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;

class ProdutoService
{
    protected $repo;

    public function __construct(ProdutoRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listar()
    {
        return $this->repo->all();
    }

    public function listarPorCantina(int $idCantina)
    {
        return $this->repo->findByCantina($idCantina);
    }

    public function criar(array $dados)
    {
        return $this->repo->create($dados);
    }

    public function atualizar(int $id, array $dados)
    {
        return $this->repo->update($id, $dados);
    }

    public function deletar(int $id)
    {
        return $this->repo->delete($id);
    }
}
