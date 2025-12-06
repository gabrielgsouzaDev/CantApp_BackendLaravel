<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido; // Importação da Model
use App\Services\PedidoService;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; // Usar Auth para obter o usuário autenticado

class PedidoController extends Controller
{
    protected $service;

    public function __construct(PedidoService $service)
    {
        $this->service = $service;
        // CRÍTICO R3: Protege o Controller com middleware de autenticação (Sanctum/JWT)
        $this->middleware('auth:sanctum');
    }

    // LISTAGEM GERAL (Apenas para Super-Admin ou fluxo de Cantina)
    public function index()
    {
        // R3: A Policy deve permitir viewAny. Se não for Admin, ele passará, mas o Service deve filtrar.
        $this->authorize('viewAny', Pedido::class);
        
        // R3: O Service deve receber o usuário para aplicar o filtro correto (Admin vê tudo, Cantineiro/Aluno usa rotas dedicadas)
        // Optamos por desativar o 'index' para Cantineiro/Aluno, forçando rotas específicas.
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
            // R3: A Policy já autorizou o viewAny. O service fará o filtro final.
            $pedidos = $this->service->getPedidosByComprador($userId);
            return response()->json(['data' => $pedidos]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar pedidos do usuário.'], 500);
        }
    }
    
    // LISTAGEM POR CANTINA (Cantineiro)
    public function getOrdersByCanteen()
    {
        // CRÍTICO R11: O ID da cantina é injetado do token do Cantineiro.
        $cantinaId = Auth::user()->id_cantina; 

        // R3: Garantia de que o usuário tem role 'cantineiro' (será filtrado pela Policy ou Middleware)
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
            
            // CRÍTICO R3: Autorização granular: o usuário pode ver ESTE pedido?
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
        // CRÍTICO R3: Autorização de criação
        $this->authorize('create', Pedido::class);
        
// CRÍTICO R13: Validação Refatorada para esperar snake_case (padrão Laravel)
        $data = $request->validate([
            // ... (Campos de Pedido)
            'id_cantina' => 'required|integer',
            'id_comprador' => 'required|integer',
            'id_destinatario' => 'required|integer',
            'valor_total' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'status' => ['required', 'string', Rule::in(['pendente'])], 
            'items' => 'required|array', 
            
            // CRÍTICO R13: REFATORADO para snake_case no INPUT
            'items.*.product_id' => 'required|integer|exists:produtos,id', // Era productId
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', // Era unitPrice
        ]);
        
        // CRÍTICO R3: Injeção do ID do Comprador do Token (Garante Self-Authorization)
        if (Auth::id() !== $data['id_comprador']) {
             return response()->json(['message' => 'Você não tem permissão para criar pedidos em nome de outro usuário.'], 403);
        }

        try {
            // ... $pedido = $this->service->createOrderWithItems($data);
            // O PedidoService e Repository já esperam snake_case para o banco.
            $pedido = $this->service->createOrderWithItems($data);
            return response()->json($pedido, 201);
        } catch (ValidationException $e) {
            // ... (Tratamento de erros 422)
            return response()->json(['message' => 'Erro de validação na criação do pedido.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha na criação do pedido: A transação foi revertida.'], 500);
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