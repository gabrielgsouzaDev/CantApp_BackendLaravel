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

/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS (SEM TOKEN)
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('users', [UserController::class, 'store'])->name('register');

// Escolas públicas (necessário para cadastro e visualização inicial)
Route::apiResource('escolas', EscolaController::class)->only(['index', 'show', 'store']);

Route::get('planos', [PlanoController::class, 'index']);
Route::apiResource('enderecos', EnderecoController::class)->only(['store']);


/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (AUTH:SANTCUM)
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


    /* ================= ESCOLAS ================= */
    Route::apiResource('escolas', EscolaController::class)->except(['store']);


    /* ================= CANTINAS ================= */

    // ✅ CRÍTICO PARA O FRONTEND - rota exigida pelo dashboard da escola
    Route::get('cantinas/escola/{id_escola}', [CantinaController::class, 'getBySchool']);

    Route::apiResource('cantinas', CantinaController::class);


    /* ================= PRODUTOS ================= */

    // ✅ CRÍTICO PARA O FRONTEND - produtos por cantina
    Route::get('cantinas/{id_cantina}/produtos', [ProdutoController::class, 'getByCanteen']);

    Route::apiResource('produtos', ProdutoController::class);


    /* ================= ESTOQUE ================= */
    Route::apiResource('estoques', EstoqueController::class);


    /* ================= PEDIDOS ================= */
    Route::apiResource('pedidos', PedidoController::class);
    Route::apiResource('itens-pedido', ItemPedidoController::class);


    /* ================= FINANCEIRO ================= */
    Route::apiResource('carteiras', CarteiraController::class);
    Route::apiResource('transacoes', TransacaoController::class);


    /* ================= CONTROLE PARENTAL ================= */
    Route::apiResource('controle-parental', ControleParentalController::class);
    Route::apiResource('controle-parental-produto', ControleParentalProdutoController::class);
    Route::apiResource('user-dependencia', UserDependenciaController::class);
});