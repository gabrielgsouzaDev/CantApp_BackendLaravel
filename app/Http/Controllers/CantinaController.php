<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CantinaService;

class CantinaController extends Controller
{
    protected $service;

    public function __construct(CantinaService $service)
    {
        $this->service = $service;
    }

    // Listar todas as cantinas
    public function index()
    {
        $cantinas = $this->service->all();
        return response()->json(['data' => $cantinas]);
    }

    // Mostrar uma cantina específica
    public function show($id)
    {
        $cantina = $this->service->find($id);

        if (!$cantina) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => $cantina]);
    }

    // Criar cantina
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'id_escola' => 'required|integer',
            'hr_abertura' => 'nullable|date_format:H:i:s',
            'hr_fechamento' => 'nullable|date_format:H:i:s'
        ]);

        $cantina = $this->service->create($data);

        return response()->json(['data' => $cantina], 201);
    }

    // Atualizar cantina
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $cantina = $this->service->update($id, $data);

        if (!$cantina) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => $cantina]);
    }

    // Deletar cantina
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
