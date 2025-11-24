<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarteiraService;
use Illuminate\Support\Facades\DB;
use App\Models\Carteira;
use App\Models\TransacaoCarteira;

class CarteiraController extends Controller
{
    protected $service;

    public function __construct(CarteiraService $service)
    {
        $this->service = $service;
    }

    /**
     * Lista todas as carteiras.
     */
    public function index()
    {
        return response()->json($this->service->all());
    }

    /**
     * Retorna uma carteira específica pelo ID da carteira.
     */
    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    /**
     * Cria uma nova carteira.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer|unique:tb_carteira,id_user',
            'saldo' => 'numeric',
            'saldo_bloqueado' => 'numeric',
            'limite_recarregar' => 'numeric',
            'limite_maximo_saldo' => 'numeric'
        ]);

        return response()->json($this->service->create($data));
    }

    /**
     * Atualiza uma carteira existente.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    /**
     * Deleta uma carteira.
     */
    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }

    /**
     * Busca a carteira de um usuário pelo ID do usuário. (NOVA ROTA)
     */
    public function getWalletByUser(string $id_usuario)
    {
        try {
            // Assumindo que a coluna na tabela é 'id_user'
            $carteira = Carteira::where('id_user', $id_usuario)->firstOrFail(); 
            return response()->json($carteira->load('user'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Carteira do usuário não encontrada.'], 404);
        }
    }

    /**
     * Processa a recarga (crédito) na carteira de um usuário de forma segura.
     * Esta é a função corrigida que estava causando o Erro 500.
     */
    public function recharge(Request $request)
    {
        // 1. Validação dos dados de recarga
        $validated = $request->validate([
            // Use 'id' se for o campo PK na tabela 'users', ou ajuste para 'id_user' se for o nome do campo
            'user_id' => 'required|exists:users,id', 
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string', 
        ]);

        $userId = $validated['user_id'];
        $valorRecarga = $validated['valor'];
        $descricao = $request->input('descricao', 'Recarga via Pagamento');

        try {
            // INÍCIO DA TRANSAÇÃO: Garante a atomicidade
            DB::beginTransaction();

            // 2. Busca e Bloqueio da Carteira
            // CRÍTICO: lockForUpdate() impede modificações simultâneas (concorrência)
            $carteira = Carteira::where('id_user', $userId) 
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
                // Ajuste 'id_carteira' se o campo for diferente (e.g., 'carteira_id')
                'id_carteira' => $carteira->id, 
                'tipo' => 'CREDITO',
                'valor' => $valorRecarga,
                'descricao' => $descricao,
                'id_pedido' => null,
            ]);

            // FIM DA TRANSAÇÃO: Se tudo deu certo, commita
            DB::commit();

            // 5. Resposta de Sucesso
            return response()->json([
                'message' => 'Recarga realizada com sucesso.',
                'saldo_atual' => $carteira->saldo,
                'carteira' => $carteira->load('user'), 
            ], 200);

        } catch (\Exception $e) {
            // Em caso de qualquer erro (ex: banco de dados, limite de recarga), desfaz
            DB::rollBack();
            \Log::error("Erro na Recarga da Carteira (UserID: {$userId}): " . $e->getMessage());
            
            // Retorna 500 para sinalizar falha interna
            return response()->json([
                'message' => 'Falha interna ao processar a recarga.', 
                // Detalhe do erro para debug, remova em produção
                'debug_error' => $e->getMessage()
            ], 500); 
        }
    }
}