<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransacaoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransacaoController extends Controller
{
    protected $service;

    public function __construct(TransacaoService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar todas as transações
     */
    public function index()
    {
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
            return response()->json(['data' => $transacao]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transação: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Criar nova transação
     */
    public function store(Request $request)
    {
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
     * Atualizar transação
     */
    public function update(Request $request, $id)
    {
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
     * Deletar transação
     */
    public function destroy($id)
    {
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
        try {
            $transacoes = $this->service->getTransacoesByUserId($id_usuario);
            return response()->json(['data' => $transacoes]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transações do usuário: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Buscar transações de uma cantina específica
     */
    public function getTransactionsByCanteen(string $id_cantina)
    {
        try {
            $transacoes = $this->service->getTransacoesByCanteenId($id_cantina);
            return response()->json(['data' => $transacoes]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transações da cantina: ' . $e->getMessage()], 500);
        }
    }
}
