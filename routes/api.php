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

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::post('token/refresh', [AuthController::class, 'refresh']);
});

// Roles e UserRoles
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::post('user-role', [UserRoleController::class, 'store']);
    Route::delete('user-role', [UserRoleController::class, 'destroy']);
});

// Usuários
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
});

// Grupo 2 - Escola / Cantina
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('escolas', EscolaController::class);
    Route::apiResource('cantinas', CantinaController::class);
    Route::apiResource('planos', PlanoController::class);
    Route::apiResource('enderecos', EnderecoController::class);
    Route::apiResource('produtos', ProdutoController::class);
});

// Pedidos e Itens
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('pedidos', PedidoController::class);
    Route::apiResource('itens-pedido', ItemPedidoController::class);
});

// Carteira e transações
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('carteiras', CarteiraController::class);
    Route::apiResource('transacoes', TransacaoController::class);
});

// Controle parental
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('controle-parental', ControleParentalController::class);
    Route::apiResource('controle-parental-produto', ControleParentalProdutoController::class);
    Route::apiResource('user-dependencia', UserDependenciaController::class);
    Route::apiResource('estoques', EstoqueController::class);
});
