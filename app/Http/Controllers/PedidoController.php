<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use App\Helpers\ResponseHelper;

class PedidoController extends Controller
{
    protected $service;

    public function __construct(PedidoService $service)
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
            'aluno_id' => 'nullable|exists:alunos,id',
            'responsavel_id' => 'nullable|exists:responsaveis,id',
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1'
        ]);

        return ResponseHelper::success(
            $this->service->criar($dados)
        );
    }

    public function show($id)
    {
        return ResponseHelper::success(
            $this->service->repo->find($id)
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $dados = $request->validate([
            'status' => 'required'
        ]);

        return ResponseHelper::success(
            $this->service->atualizarStatus($id, $dados['status'])
        );
    }

    public function destroy($id)
    {
        return ResponseHelper::success(
            $this->service->deletar($id)
        );
    }
}
