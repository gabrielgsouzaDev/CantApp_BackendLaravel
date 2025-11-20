<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdutoService;
use App\Helpers\ResponseHelper;

class ProdutoController extends Controller
{
    protected $service;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success($this->service->listar());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'categoria' => 'required'
        ]);

        return ResponseHelper::success($this->service->criar($dados));
    }

    public function show($id)
    {
        return ResponseHelper::success($this->service->repo->find($id));
    }

    public function update(Request $request, $id)
    {
        return ResponseHelper::success($this->service->atualizar($id, $request->all()));
    }

    public function destroy($id)
    {
        return ResponseHelper::success($this->service->deletar($id));
    }
}
