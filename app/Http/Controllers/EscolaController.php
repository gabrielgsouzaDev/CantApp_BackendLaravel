<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EscolaService;

class EscolaController extends Controller
{
    protected $service;

    public function __construct(EscolaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $escolas = $this->service->all();
        return response()->json(['data' => $escolas]);
    }

    public function show($id)
    {
        $escola = $this->service->find($id);

        if (!$escola) {
            return response()->json(['error' => 'Escola não encontrada'], 404);
        }

        return response()->json(['data' => $escola]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'cnpj' => 'nullable|string',
            'id_endereco' => 'nullable|integer',
            'id_plano' => 'nullable|integer',
            'status' => 'required|string',
            'qtd_alunos' => 'required|integer'
        ]);

        $escola = $this->service->create($data);

        return response()->json(['data' => $escola], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $escola = $this->service->update($id, $data);

        if (!$escola) {
            return response()->json(['error' => 'Escola não encontrada'], 404);
        }

        return response()->json(['data' => $escola]);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Escola não encontrada'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
