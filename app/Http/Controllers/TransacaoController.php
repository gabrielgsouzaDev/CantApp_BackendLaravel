<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransacaoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth; // Necessário para futura proteção R3

class TransacaoController extends Controller
{
    protected $service;

    public function __construct(TransacaoService $service)
    {
        $this->service = $service;
        // R3: Adicionar proteção de Middleware/Policy aqui ou nas rotas.
        // $this->middleware('auth:sanctum');
    }

    /**
     * Listar todas as transações (Apenas Admin/Auditor)
     */
    public function index()
    {
        // R3: Policy deve ser aplicada aqui: $this->authorize('viewAny', Transacao::class);
        return response()->json(['data' => $this->service->all()]);
    }

    /**
     * Mostrar uma transação específica
     */
    public function show($id)
    {
        try {
            $transacao = $this->service->find($id);
            if (!$transacao) {
                return response()->json(['message' => 'Transação não encontrada'], 404);
            }
            // R3: Policy deve garantir que apenas o autor/aprovador/admin possa ver.
            // Ex: $this->authorize('view', $transacao);
            return response()->json(['data' => $transacao]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transação: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Criar nova transação (Usado pelo Backend, não diretamente pela API)
     */
    public function store(Request $request)
    {
        // R3: Esta rota deve ser restrita apenas a Admin ou chamada por Service Interno.
        $data = $request->validate([
            'id_carteira' => 'required|integer',
            'id_user_autor' => 'required|integer',
            'id_aprovador' => 'nullable|integer',
            'uuid' => 'required|string',
            'tipo' => 'required|string',
            'valor' => 'required|numeric',
            'descricao' => 'nullable|string',
            'referencia' => 'nullable|string',
            'status' => 'required|string'
        ]);

        try {
            $transacao = $this->service->create($data);
            return response()->json(['data' => $transacao], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar transação: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Atualizar transação (Apenas Admin/Auditor)
     */
    public function update(Request $request, $id)
    {
        // R3: Policy deve ser aplicada aqui.
        $data = $request->all();

        try {
            $transacao = $this->service->update($id, $data);
            if (!$transacao) {
                return response()->json(['message' => 'Transação não encontrada'], 404);
            }
            return response()->json(['data' => $transacao]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar transação: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Deletar transação (Apenas Admin/Auditor)
     */
    public function destroy($id)
    {
        // R3: Policy deve ser aplicada aqui.
        try {
            $deleted = $this->service->delete($id);
            if (!$deleted) {
                return response()->json(['message' => 'Transação não encontrada'], 404);
            }
            return response()->json(['data' => ['deleted' => true]]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar transação: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Buscar transações de um usuário específico
     */
    public function getTransactionsByUser(string $id_usuario)
    {
        // R3: A Policy DEVE garantir que o $id_usuario da rota seja o mesmo do Auth::id().
        // Ex: if (Auth::id() != $id_usuario) return 403;
        
        try {
            // CRÍTICO R18: Chamada correta ao Service.
            $transacoes = $this->service->getTransactionsByUser($id_usuario); 
            return response()->json(['data' => $transacoes]);
        } catch (\Exception $e) {
            // Tratamento de erro geral, mantido 500 por segurança.
            return response()->json(['message' => 'Erro ao buscar transações do usuário: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Buscar transações de uma cantina específica
     */
    public function getTransactionsByCanteen(string $id_cantina)
    {
        // R3: Policy deve garantir que o Cantineiro só veja transações da sua cantina.
        
        try {
            // CRÍTICO R18: Chamada correta ao Service.
            $transacoes = $this->service->getTransactionsByCanteenId($id_cantina);
            return response()->json(['data' => $transacoes]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transações da cantina: ' . $e->getMessage()], 500);
        }
    }
}