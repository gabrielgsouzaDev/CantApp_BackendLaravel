<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EscolaService;
use App\Helpers\ResponseHelper;

class EscolaController extends Controller
{
    protected $service;

    public function __construct(EscolaService $service)
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
            'endereco' => 'required'
            // adicione outros campos da tabela tb_escola se houver
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
