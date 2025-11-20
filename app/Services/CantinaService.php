<?php

namespace App\Services;

use App\Repositories\CantinaRepository;

class CantinaService
{
    protected $repo;

    public function __construct(CantinaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listar()
    {
        return $this->repo->all();
    }

    public function listarPorEscola(int $idEscola)
    {
        return $this->repo->findByEscola($idEscola);
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
