<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ItemPedidoService;
use App\Models\Produto;

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
            'quantidade' => 'required|integer'
        ]);

        // Busca o preço do produto no backend
        $produto = Produto::find($data['id_produto']);
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $data['preco_unitario'] = $produto->price;

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
