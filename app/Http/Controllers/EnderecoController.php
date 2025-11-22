<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnderecoService;

class EnderecoController extends Controller
{
    protected $service;

    public function __construct(EnderecoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $enderecos = $this->service->all();
        return response()->json(['data' => $enderecos]);
    }

    public function show($id)
    {
        $endereco = $this->service->find($id);

        if (!$endereco) {
            return response()->json(['error' => 'Endereço não encontrado'], 404);
        }

        return response()->json(['data' => $endereco]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cep' => 'required|string',
            'logradouro' => 'required|string',
            'numero' => 'nullable|string',
            'complemento' => 'nullable|string',
            'bairro' => 'nullable|string',
            'cidade' => 'required|string',
            'estado' => 'required|string'
        ]);

        $endereco = $this->service->create($data);

        return response()->json(['data' => $endereco], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $endereco = $this->service->update($id, $data);

        if (!$endereco) {
            return response()->json(['error' => 'Endereço não encontrado'], 404);
        }

        return response()->json(['data' => $endereco]);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Endereço não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
