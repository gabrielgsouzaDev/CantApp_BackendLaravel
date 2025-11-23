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
            'nome' => 'required|string|max:150',
            'id_endereco' => 'nullable|integer',
            'id_plano' => 'nullable|integer',
            'status' => 'nullable|string',
            'qtd_alunos' => 'nullable|integer'
        ]);

        return ResponseHelper::success($this->service->criar($dados));
    }

    public function show($id)
    {
        $escola = $this->service->buscar($id);

        if (!$escola) {
            return ResponseHelper::error('Escola não encontrada', 404);
        }

        return ResponseHelper::success($escola);
    }

    public function update(Request $request, $id)
    {
        $escola = $this->service->buscar($id);

        if (!$escola) {
            return ResponseHelper::error('Escola não encontrada', 404);
        }

        return ResponseHelper::success(
            $this->service->atualizar($id, $request->all())
        );
    }

    public function destroy($id)
    {
        $escola = $this->service->buscar($id);

        if (!$escola) {
            return ResponseHelper::error('Escola não encontrada', 404);
        }

        return ResponseHelper::success($this->service->deletar($id));
    }
}
