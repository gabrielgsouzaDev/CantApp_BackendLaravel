<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarteiraService;
use App\Exceptions\SaldoInsuficienteException;
use App\Services\UserService; 
use Exception;
use Log;
use Illuminate\Support\Facades\Auth; 

class CarteiraController extends Controller
{
    protected $service;
    protected $userService; 

    public function __construct(CarteiraService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * Listar todas as carteiras (Apenas Admin/Auditor)
     */
    public function index()
    {
        // R3: Policy deve garantir que apenas administradores tenham acesso
        return response()->json(['data' => $this->service->all()]);
    }

    /**
     * Mostrar uma carteira específica
     */
    public function show($id)
    {
        try {
            $carteira = $this->service->find($id);
            if (!$carteira) {
                return response()->json(['message' => 'Carteira não encontrada'], 404);
            }
            return response()->json(['data' => $carteira]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar carteira: ' . $e->getMessage()], 400);
        }
    }
    
    /**
     * Buscar carteira pelo ID do usuário (CRÍTICO: Usada pelo Front-end)
     * Rota: GET /api/carteiras/usuario/{id_usuario}
     */
    public function getWalletByUser($id_usuario)
    {
        $userId = (int) $id_usuario;
        try {
            $carteira = $this->service->findByUserId($userId);
            if (!$carteira) {
                return response()->json(['message' => 'Carteira não encontrada para este usuário.'], 404);
            }
            return response()->json(['data' => $carteira]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar carteira: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Criar nova carteira (Normalmente usado internamente, via UserObserver)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer|unique:users,id',
            'saldo' => 'nullable|numeric|min:0',
        ]);
        
        try {
            $carteira = $this->service->create($data);
            return response()->json(['data' => $carteira], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao criar carteira: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Atualizar carteira
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            $carteira = $this->service->update($id, $data);
            if (!$carteira) {
                return response()->json(['message' => 'Carteira não encontrada'], 404);
            }
            return response()->json(['data' => $carteira]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar carteira: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Deletar carteira
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->service->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Carteira não encontrada'], 404);
            }
            return response()->json(['data' => ['deleted' => true]]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao deletar carteira: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Processa a recarga (crédito) na carteira de um usuário de forma segura.
     * Rota: POST /api/carteiras/recarregar (CRÍTICO: Rota de Negócio)
     */
    public function recharge(Request $request)
    {
        // 1. Validação dos dados de recarga
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id', 
            'valor' => 'required|numeric|min:0.01', 
            'descricao' => 'nullable|string',
            'referencia_id' => 'nullable|string', 
        ]);

        // Conversão de Tipos
        $userId = (int)$validated['id_user'];
        $valorRecarga = (float)$validated['valor'];
        $descricao = $request->input('descricao', 'Recarga via Pagamento');
        $referenciaId = $request->input('referencia_id', null);

        try {
            // 2. CRÍTICO R31: Captura os dois objetos que o Service retorna.
            [$carteira, $transacao] = $this->service->credit($userId, $valorRecarga, $descricao, $referenciaId); 

            // 3. Resposta de Sucesso (200 OK)
            return response()->json([
                'message' => 'Recarga realizada com sucesso.',
                'saldo_atual' => $carteira->saldo,
                
                // CRÍTICO R31: Removido o load('user') para eliminar o ponto de falha.
                'carteira' => $carteira, 
                
                'transacao' => $transacao, // CRÍTICO R29: Retorna a Transação para o Front-end mapear.
            ], 200);

        } catch (Exception $e) {
            // 4. Tratamento de Erro (Log)
            Log::error("Erro na Recarga da Carteira (UserID: {$userId}): " . $e->getMessage());
            
            // Retorno de Erro Consistente
            $statusCode = ($e instanceof SaldoInsuficienteException) ? 403 : 500;
            
            return response()->json([
                'message' => 'Falha interna ao processar a recarga.', 
                'debug_error' => $e->getMessage()
            ], $statusCode); 
        }
    }
}