<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PedidoController extends Controller
{
    protected $service;

    public function __construct(PedidoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function getOrdersByUser(string $id_usuario)
    {
        try {
            $pedidos = $this->service->getPedidosByComprador($id_usuario);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pedidos do usuário.'], 500);
        }
    }
    
    public function getOrdersByCanteen(string $id_cantina)
    {
        try {
            $pedidos = $this->service->getPedidosByCantina($id_cantina);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pedidos da cantina.'], 500);
        }
    }

    public function show($id)
    {
        try {
             return response()->json($this->service->find($id));
        } catch (ModelNotFoundException $e) {
             return response()->json(['message' => 'Pedido não encontrado.'], 404);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cantina' => 'required|integer',
            'id_comprador' => 'required|integer',
            'id_destinatario' => 'required|integer',
            'valor_total' => 'required|numeric|min:0',
            'status' => ['required', 'string', Rule::in(['pendente'])],
            'items' => 'required|array', 
            'items.*.productId' => 'required|integer|exists:produtos,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric|min:0',
        ]);

        try {
            $pedido = $this->service->createOrderWithItems($data);
            return response()->json($pedido, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => [
                'required', 
                'string',
                Rule::in(['pendente', 'confirmado', 'em_preparo', 'pronto', 'entregue', 'cancelado']),
            ],
        ]);
        
        try {
            $updatedPedido = $this->service->updateStatus($id, $data['status']);
            return response()->json($updatedPedido);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar status: ' . $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
