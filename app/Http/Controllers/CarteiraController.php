<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarteiraService;
// Importa as exceções de negócio
use App\Exceptions\SaldoInsuficienteException;
use Exception;
use Log; // Para logging de erro

class CarteiraController extends Controller
{
    protected $service;

    public function __construct(CarteiraService $service)
    {
        // Se a injeção do CarteiraService falhar aqui, o 500 ocorre. 
        // Garanta que CarteiraRepository e suas dependências existam.
        $this->service = $service;
    }

    // ... Métodos index, show, store, update, destroy, getWalletByUser ...
    // Mantenha os demais métodos originais ou use os do Service, se aplicável.

    /**
     * Processa a recarga (crédito) na carteira de um usuário de forma segura,
     * delegando a lógica transacional ao CarteiraService.
     * Rota: /api/carteiras/recarregar
     */
    public function recharge(Request $request)
    {
        // 1. Validação dos dados de recarga
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id', 
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string', 
        ]);

        $userId = $validated['id_user'];
        $valorRecarga = $validated['valor'];
        $descricao = $request->input('descricao', 'Recarga via Pagamento');

        try {
            // 2. DELEGAÇÃO: O Service faz a transação, lockForUpdate e registro.
            $carteira = $this->service->credit($userId, $valorRecarga, $descricao);

            // 3. Resposta de Sucesso
            return response()->json([
                'message' => 'Recarga realizada com sucesso.',
                'saldo_atual' => $carteira->saldo,
                'carteira' => $carteira->load('user'), 
            ], 200);

        } catch (Exception $e) {
            // 4. Tratamento de Erro
            Log::error("Erro na Recarga da Carteira (UserID: {$userId}): " . $e->getMessage());
            
            // Trata exceções de negócio (Saldo Insuficiente) ou genéricas
            $statusCode = ($e instanceof SaldoInsuficienteException) ? 403 : 500;
            
            return response()->json([
                'message' => 'Falha interna ao processar a recarga.', 
                'debug_error' => $e->getMessage()
            ], $statusCode); 
        }
    }
}