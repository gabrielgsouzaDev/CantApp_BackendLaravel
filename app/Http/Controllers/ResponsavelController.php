<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponsavelService;
use App\Helpers\ResponseHelper;

class ResponsavelController extends Controller
{
    protected $service;

    public function __construct(ResponsavelService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success(
            $this->service->listar()
        );
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:responsaveis,email',
            'senha' => 'required|min:6',
            'telefone' => 'required'
        ]);

        // Aqui você converte pra 'senha_hash'
        $dados['senha_hash'] = bcrypt($dados['senha']);
        unset($dados['senha']);

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

    public function update(Request $request, $id)
    {
        return ResponseHelper::success(
            $this->service->atualizar($id, $request->all())
        );
    }

    public function destroy($id)
    {
        return ResponseHelper::success(
            $this->service->deletar($id)
        );
    }
}
