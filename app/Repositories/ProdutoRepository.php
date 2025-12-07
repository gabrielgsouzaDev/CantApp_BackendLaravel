<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    protected $model;

    public function __construct(Produto $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['cantina', 'itensPedido', 'estoque', 'controleParental'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['cantina', 'itensPedido', 'estoque', 'controleParental'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $produto = $this->model->find($id);
        if ($produto) {
            $produto->update($data);
            return $produto;
        }
        return null;
    }

    public function delete($id)
    {
        $produto = $this->model->find($id);
        if ($produto) {
            return $produto->delete();
        }
        return false;
    }

    /**
     * CRÍTICO R20: Adiciona o método necessário para buscar produtos por Cantina.
     * Assume que a chave estrangeira é 'id_cantina' no modelo Produto.
     * @param string $cantinaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByCantina(string $cantinaId)
    {
        return $this->model
            ->where('id_cantina', $cantinaId)
            ->with(['cantina', 'estoque']) // Carrega dados essenciais para o frontend
            ->orderBy('nome', 'asc')
            ->get();
    }
}