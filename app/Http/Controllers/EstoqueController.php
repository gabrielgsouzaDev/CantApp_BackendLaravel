<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EstoqueService;

class EstoqueController extends Controller
{
    protected $service;

    public function __construct(EstoqueService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $itens = $this->service->all();
        return response()->json(['data' => $itens]);
    }

    public function show($id)
    {
        $item = $this->service->find($id);

        if (!$item) {
            return response()->json(['error' => 'Item de estoque não encontrado'], 404);
        }

        return response()->json(['data' => $item]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_produto' => 'required|integer',
            'quantidade' => 'required|integer'
        ]);

        $item = $this->service->create($data);

        return response()->json(['data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = $this->service->update($id, $data);

        if (!$item) {
            return response()->json(['error' => 'Item de estoque não encontrado'], 404);
        }

        return response()->json(['data' => $item]);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Item de estoque não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
