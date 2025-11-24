<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\CantinaController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ItemPedidoController;
use App\Http\Controllers\CarteiraController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\ControleParentalController;
use App\Http\Controllers\ControleParentalProdutoController;
use App\Http\Controllers\UserDependenciaController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\ProdutoFavoritoController;

/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS (SEM TOKEN)
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('users', [UserController::class, 'store'])->name('register');

// Escolas públicas
Route::apiResource('escolas', EscolaController::class)->only(['index', 'show', 'store']);

Route::get('planos', [PlanoController::class, 'index']);
Route::apiResource('enderecos', EnderecoController::class)->only(['store']);

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (AUTH:SANCTUM)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /* ================= AUTH ================= */
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::post('token/refresh', [AuthController::class, 'refresh']);

    /* ================= ROLES ================= */
    Route::apiResource('roles', RoleController::class);
    Route::post('user-role', [UserRoleController::class, 'store']);
    Route::delete('user-role', [UserRoleController::class, 'destroy']);

    /* ================= USUÁRIOS ================= */
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    /* ================= ESCOLAS PRIVADAS ================= */
    Route::apiResource('escolas', EscolaController::class)->only(['update', 'destroy']);

    /* ================= CANTINAS ================= */
    Route::get('cantinas/escola/{id_escola}', [CantinaController::class, 'getBySchool']);
    Route::apiResource('cantinas', CantinaController::class);

    /* ================= PRODUTOS ================= */
    Route::get('cantinas/{id_cantina}/produtos', [ProdutoController::class, 'getByCanteen']);
    Route::apiResource('produtos', ProdutoController::class);

    /* ================= FAVORITOS ================= */
    // ✔ Seguro, simples e REST
    Route::get('favoritos/{id_user}', [ProdutoFavoritoController::class, 'index']);
    Route::post('favoritos', [ProdutoFavoritoController::class, 'store']);
    Route::delete('favoritos/{id_user}/{id_produto}', [ProdutoFavoritoController::class, 'destroy']);

    /* ================= ESTOQUE ================= */
    Route::apiResource('estoques', EstoqueController::class);

    /* ================= PEDIDOS ================= */
// src/routes/api.php

/* ================= PEDIDOS ================= */
    Route::apiResource('pedidos', PedidoController::class);
    // Rota para listagem por aluno (já definida)
    Route::get('pedidos/usuario/{id_usuario}', [PedidoController::class, 'getOrdersByUser']);
    // ⭐ NOVO: Rota para listagem por cantina (essencial para cantineiros)
    Route::get('pedidos/cantina/{id_cantina}', [PedidoController::class, 'getOrdersByCanteen']); 
    // Rota para atualização de status (já definida)
    Route::patch('pedidos/{pedido}/status', [PedidoController::class, 'updateStatus']); 
    Route::apiResource('itens-pedido', ItemPedidoController::class);

    /* ================= FINANCEIRO ================= */
    Route::apiResource('carteiras', CarteiraController::class);
    Route::apiResource('transacoes', TransacaoController::class);
    Route::get('transacoes/usuario/{id_usuario}', [TransacaoController::class, 'getTransactionsByUser']);
    Route::get('transacoes/cantina/{id_cantina}', [TransacaoController::class, 'getTransactionsByCanteen']);

    /* ================= CONTROLE PARENTAL ================= */
    Route::apiResource('controle-parental', ControleParentalController::class);
    Route::apiResource('controle-parental-produto', ControleParentalProdutoController::class);
    Route::apiResource('user-dependencia', UserDependenciaController::class);
});
