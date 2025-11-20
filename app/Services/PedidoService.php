<?php

namespace App\Services;

use App\Repositories\PedidoRepository;
use App\Models\Produto;

class PedidoService
{
    protected $repo;

    public function __construct(PedidoRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listar()
    {
        return $this->repo->all();
    }

    public function listarPorDestinatario(string $tipo, int $id)
    {
        return $this->repo->findByDestinatario($tipo, $id);
    }

    public function criar(array $dados)
    {
        // Validar produtos
        foreach ($dados['produtos'] as &$p) {
            $p['produto'] = Produto::findOrFail($p['id']);
        }

        return $this->repo->create($dados);
    }

    public function atualizarStatus(int $id, string $status)
    {
        return $this->repo->update($id, ['status' => $status]);
    }

    public function deletar(int $id)
    {
        return $this->repo->delete($id);
    }
}
