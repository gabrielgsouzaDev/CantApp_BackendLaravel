<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ItemPedidoService;

class ItemPedidoController extends Controller
{
    protected $service;

    public function __construct(ItemPedidoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pedido' => 'required|integer',
            'id_produto' => 'required|integer',
            'quantidade' => 'required|integer',
            'preco_unitario' => 'required|numeric'
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
