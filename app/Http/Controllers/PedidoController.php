<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido; 
use App\Services\PedidoService;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; 

class PedidoController extends Controller
{
    protected $service;

    public function __construct(PedidoService $service)
    {
        $this->service = $service;
        // CRÍTICO R3: Protege o Controller
        $this->middleware('auth:sanctum');
    }

    // LISTAGEM GERAL (Apenas para Super-Admin ou fluxo de Cantina)
    public function index()
    {
        $this->authorize('viewAny', Pedido::class);
        
        if (Auth::user()->role !== 'admin') {
             return response()->json(['message' => 'Acesso negado. Use as rotas de listagem específicas.'], 403);
        }
        
        return response()->json(['data' => $this->service->all()]);
    }

    // LISTAGEM POR ALUNO (Cliente)
    public function getOrdersByUser()
    {
        // CRÍTICO R11: O ID do usuário NÃO é pego da URL. É injetado do token.
        $userId = Auth::id(); 
        
        try {
            $pedidos = $this->service->getPedidosByComprador($userId);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pedidos do usuário.'], 500);
        }
    }
    
    // LISTAGEM POR CANTINA (Cantineiro)
    public function getOrdersByCanteen()
    {
        $cantinaId = Auth::user()->id_cantina; 

        if (Auth::user()->role !== 'cantineiro') {
             return response()->json(['message' => 'Acesso negado. Apenas Cantineiros podem usar esta rota.'], 403);
        }

        try {
            $pedidos = $this->service->getPedidosByCantina($cantinaId);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pedidos da cantina.'], 500);
        }
    }

    // VISUALIZAÇÃO DE UM PEDIDO
    public function show($id)
    {
        try {
            $pedido = $this->service->find($id);
            $this->authorize('view', $pedido);
            
            return response()->json($pedido);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
             return response()->json(['message' => 'Acesso negado para este pedido.'], 403);
        }
    }

    // CRIAÇÃO DE NOVO PEDIDO
    public function store(Request $request)
    {
        // CRÍTICO R3: Autorização de criação (checa o role do usuário)
        $this->authorize('create', Pedido::class);
        
        // CRÍTICO R13: Validação Refatorada para esperar snake_case (padrão Laravel)
        $data = $request->validate([
            'id_cantina' => 'required|integer|exists:tb_cantina,id_cantina',
            'id_comprador' => 'required|integer|exists:users,id',
            'id_destinatario' => 'required|integer|exists:users,id',
            'valor_total' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'status' => ['required', 'string', Rule::in(['pendente'])], 
            'items' => 'required|array', 
            
            // CRÍTICO R13: REFATORADO para snake_case no INPUT
            'items.*.product_id' => 'required|integer|exists:tb_produto,id_produto', 
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', 
        ]);
        
        // CRÍTICO R3: Sobrescrever o ID do Comprador com o ID do Token (INJEÇÃO DE SEGURANÇA)
        // Isso anula a necessidade da checagem de Auth::id() === id_comprador na requisição.
        $data['id_comprador'] = Auth::id();

        try {
            // O PedidoService e Repository já esperam snake_case para o banco.
            $pedido = $this->service->createOrderWithItems($data);
            return response()->json($pedido, 201);
        } catch (ValidationException $e) {
            // ... (Tratamento de erros 422)
            return response()->json(['message' => 'Erro de validação na criação do pedido.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha na criação do pedido: A transação foi revertida.', 'debug_error' => $e->getMessage()], 500);
        }
    }

    // ATUALIZAÇÃO SEGURA E VALIDADA DE STATUS (Cantineiro)
    public function updateStatus(Request $request, $id)
    {
        try {
            // R3: Busca o pedido para que o 'authorize' possa checar o 'id_cantina'
            $pedido = $this->service->find($id); 
            $this->authorize('update', $pedido);

            $data = $request->validate([
                'status' => [
                    'required', 
                    'string',
                    Rule::in(['confirmado', 'em_preparo', 'pronto', 'entregue', 'cancelado']),
                ],
            ]);
            
            $updatedPedido = $this->service->updateStatus($id, $data['status']);
            return response()->json($updatedPedido);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
             return response()->json(['message' => 'Acesso negado (Cantina ou Permissão).'], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar status: ' . $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $pedido = $this->service->find($id);
            // CRÍTICO R3: Autorização de Deleção/Cancelamento
            $this->authorize('delete', $pedido);
            
            return response()->json(['deleted' => $this->service->delete($id)]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
             return response()->json(['message' => 'Acesso negado para exclusão.'], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao tentar deletar pedido.'], 400);
        }
    }
}