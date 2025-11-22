<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdutoService;

class ProdutoController extends Controller
{
    protected $service;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
    }

    // Listar todos os produtos
    public function index()
    {
        $produtos = $this->service->all();
        return response()->json(['data' => $produtos]);
    }

    // Mostrar um produto específico
    public function show($id)
    {
        $produto = $this->service->find($id);

        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        return response()->json(['data' => $produto]);
    }

    // Criar produto
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cantina' => 'required|integer',
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'ativo' => 'boolean'
        ]);

        $produto = $this->service->create($data);

        return response()->json(['data' => $produto], 201);
    }

    // Atualizar produto
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $produto = $this->service->update($id, $data);

        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        return response()->json(['data' => $produto]);
    }

    // Deletar produto
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
