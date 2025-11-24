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
        // O PedidoService deve ser injetado para lidar com toda a lógica de negócio
        $this->service = $service;
    }

    // Retorna todos os pedidos (geralmente restrito a admins ou usado internamente no service)
    public function index()
    {
        return response()->json(['data' => $this->service->all()]);
    }

    // NOVO: Retorna pedidos filtrados pelo ID do comprador (Aluno ou Pai)
    // Rota: GET /api/pedidos/usuario/{id_usuario}
    public function getOrdersByUser(string $id_usuario)
    {
        try {
            // O Service deve filtrar por id_comprador ou id_destinatario, dependendo da sua regra.
            $pedidos = $this->service->getPedidosByComprador($id_usuario);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            // Logar o erro se necessário
            return response()->json(['message' => 'Erro ao buscar pedidos do usuário.'], 500);
        }
    }
    
    // NOVO: Retorna pedidos filtrados pelo ID da cantina (Para Cantineiro)
    // Rota: GET /api/pedidos/cantina/{id_cantina}
    public function getOrdersByCanteen(string $id_cantina)
    {
        // 🚨 IMPORTANTE: Adicione aqui a autorização
        // $this->authorize('viewCantinaOrders', $id_cantina); 

        try {
            // O Service deve garantir a busca pela cantina
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

    // CORRIGIDO: Validação para incluir a estrutura de itens (o que o frontend envia)
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cantina' => 'required|integer',
            'id_comprador' => 'required|integer', // Quem pagou (Pai)
            'id_destinatario' => 'required|integer', // Quem receberá (Aluno)
            'valor_total' => 'required|numeric|min:0',
            'status' => ['required', 'string', Rule::in(['pendente'])], // Deve começar como pendente
            // Validação dos Itens do Pedido
            'items' => 'required|array', 
            'items.*.productId' => 'required|integer|exists:produtos,id', // Exemplo de validação de existência
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric|min:0',
        ]);

        try {
            // O Service deve ter um método que lida com a criação do Pedido + Itens do Pedido
            $pedido = $this->service->createOrderWithItems($data);
            return response()->json($pedido, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha ao criar pedido: ' . $e->getMessage()], 400);
        }
    }

    // Rota Padrão para PUT/PATCH /pedidos/{id}
    public function update(Request $request, $id)
    {
        // 🚨 Recomendado: Adicionar validação aqui para garantir a integridade dos dados
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }
    
    // NOVO: Rota específica para atualização de status (Cancelamento, Confirmação, etc.)
    // Rota: PATCH /api/pedidos/{pedido}/status
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            // Restringe o status apenas a valores válidos para o sistema
            'status' => [
                'required', 
                'string',
                Rule::in(['pendente', 'confirmado', 'em_preparo', 'pronto', 'entregue', 'cancelado']),
            ],
        ]);
        
        try {
            // O Service deve ter um método dedicado para esta ação.
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