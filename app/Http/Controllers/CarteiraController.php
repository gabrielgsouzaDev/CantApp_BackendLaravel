<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarteiraService;

class CarteiraController extends Controller
{
    protected $service;

    public function __construct(CarteiraService $service)
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
            'id_user' => 'required|integer',
            'saldo' => 'numeric',
            'saldo_bloqueado' => 'numeric',
            'limite_recarregar' => 'numeric',
            'limite_maximo_saldo' => 'numeric'
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
    public function recharge(Request $request)
    {
        // 1. Validação dos dados de recarga
        // Idealmente, usar um FormRequest, mas para o exemplo, usamos validate()
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'valor' => 'required|numeric|min:0.01',
            // 'descricao' => 'nullable|string', // Se quiser especificar a descrição (e.g., PIX/Boleto)
        ]);

        $userId = $validated['user_id'];
        $valorRecarga = $validated['valor'];
        $descricao = $request->input('descricao', 'Recarga via PIX (Mock)');

        // 2. Busca e Bloqueio da Carteira
        try {
            // Inicia a transação de banco de dados
            DB::beginTransaction();

            // CRÍTICO: Usa lockForUpdate() para bloquear a linha e prevenir concorrência
            $carteira = Carteira::where('user_id', $userId)
                                ->lockForUpdate()
                                ->first();

            if (!$carteira) {
                DB::rollBack();
                return response()->json(['message' => 'Carteira do usuário não encontrada.'], 404);
            }

            // 3. Execução da Recarga (Crédito)
            $carteira->saldo += $valorRecarga;
            $carteira->save();

            // 4. Registro da Transação (Histórico)
            TransacaoCarteira::create([
                'id_carteira' => $carteira->id_carteira,
                'tipo' => 'CREDITO',
                'valor' => $valorRecarga,
                'descricao' => $descricao,
                'id_pedido' => null, // Não se aplica a recargas
            ]);

            DB::commit();

            // 5. Resposta de Sucesso
            return response()->json([
                'message' => 'Recarga realizada com sucesso.',
                'saldo_atual' => $carteira->saldo,
                'carteira' => $carteira->load('user'), // Retorna a carteira atualizada
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log do erro completo para debug
            \Log::error("Erro na Recarga da Carteira (UserID: {$userId}): " . $e->getMessage());
            
            return response()->json([
                'message' => 'Falha interna ao processar a recarga.', 
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}
