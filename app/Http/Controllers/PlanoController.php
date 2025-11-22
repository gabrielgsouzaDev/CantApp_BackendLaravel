<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlanoService;

class PlanoController extends Controller
{
    protected $service;

    public function __construct(PlanoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $planos = $this->service->all();
        return response()->json(['data' => $planos]);
    }

    public function show($id)
    {
        $plano = $this->service->find($id);

        if (!$plano) {
            return response()->json(['error' => 'Plano não encontrado'], 404);
        }

        return response()->json(['data' => $plano]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'preco_mensal' => 'required|numeric',
            'qtd_max_alunos' => 'required|integer',
            'qtd_max_cantinas' => 'required|integer'
        ]);

        $plano = $this->service->create($data);

        return response()->json(['data' => $plano], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $plano = $this->service->update($id, $data);

        if (!$plano) {
            return response()->json(['error' => 'Plano não encontrado'], 404);
        }

        return response()->json(['data' => $plano]);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Plano não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
