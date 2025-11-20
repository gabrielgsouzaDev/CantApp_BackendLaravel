<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CantinaService;
use App\Helpers\ResponseHelper;

class CantinaController extends Controller
{
    protected $service;

    public function __construct(CantinaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ResponseHelper::success($this->service->listar());
    }

    public function listarPorEscola(Request $request)
    {
        $request->validate([
            'id_escola' => 'required|exists:escolas,id'
        ]);

        return ResponseHelper::success($this->service->listarPorEscola($request->id_escola));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'id_escola' => 'required|exists:escolas,id'
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
