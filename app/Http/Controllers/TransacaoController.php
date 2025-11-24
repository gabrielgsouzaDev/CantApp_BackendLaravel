<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransacaoService;

class TransacaoController extends Controller
{
    protected $service;

    public function __construct(TransacaoService $service)
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

    public function getTransactionsByUser(string $id_usuario)
{
    try {
        $transacoes = $this->service->getTransacoesByUserId($id_usuario);
        return response()->json(['data' => $transacoes]); 
    } catch (\Exception $e) {
        return response()->json(['message' => 'Erro ao buscar transações do usuário.'], 500);
    }
}
    public function getTransactionsByCanteen(string $id_cantina)
    {
        try {
            $transacoes = $this->service->getTransacoesByCanteenId($id_cantina);
            return response()->json(['data' => $transacoes]); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar transações da cantina.'], 500);
        }
    }
}