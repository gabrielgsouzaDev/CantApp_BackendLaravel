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
| Rotas Públicas (sem token)
|--------------------------------------------------------------------------
*/
Route::post('login', [AuthController::class, 'login'])->name('login');

// Rotas públicas adicionais necessárias para login/cadastro
Route::get('escolas', [EscolaController::class, 'index']);
Route::get('planos', [PlanoController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Token (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::post('token/refresh', [AuthController::class, 'refresh']);

    // Roles e UserRoles
    Route::apiResource('roles', RoleController::class);
    Route::post('user-role', [UserRoleController::class, 'store']);
    Route::delete('user-role', [UserRoleController::class, 'destroy']);

    // Usuários
    Route::apiResource('users', UserController::class);

    // Grupo 2 - Escola / Cantina / Infra
    Route::apiResource('cantinas', CantinaController::class);
    Route::apiResource('enderecos', EnderecoController::class);
    Route::apiResource('produtos', ProdutoController::class);

    // Pedidos e Itens
    Route::apiResource('pedidos', PedidoController::class);
    Route::apiResource('itens-pedido', ItemPedidoController::class);

    // Carteira e Transações
    Route::apiResource('carteiras', CarteiraController::class);
    Route::apiResource('transacoes', TransacaoController::class);

    // Controle Parental
    Route::apiResource('controle-parental', ControleParentalController::class);
    Route::apiResource('controle-parental-produto', ControleParentalProdutoController::class);
    Route::apiResource('user-dependencia', UserDependenciaController::class);

    // Estoques
    Route::apiResource('estoques', EstoqueController::class);
});
