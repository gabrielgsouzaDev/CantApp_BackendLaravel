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
|
| Aqui ficam todas as rotas que podem ser acessadas sem autenticação.
| Inclui login, cadastro de usuário (POST /users), e endpoints públicos
| necessários para iniciar a aplicação, como escolas e planos.
|
*/

// Autenticação e cadastro
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('users', [UserController::class, 'store'])->name('register'); // cadastro público, endpoint = /api/users

// Endpoints públicos de suporte
Route::get('escolas', [EscolaController::class, 'index']);
Route::get('planos', [PlanoController::class, 'index']);
Route::apiResource('enderecos', EnderecoController::class);
/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Token (auth:sanctum)
|--------------------------------------------------------------------------
|
| Todas as demais rotas requerem autenticação via token. Isso inclui logout,
| CRUD de usuários (exceto store), roles, produtos, pedidos, carteiras,
| controle parental, estoque, etc.
|
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

    // Usuários (todas as ações exceto store)
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Grupo 2 - Escola / Cantina / Infra
    Route::apiResource('cantinas', CantinaController::class);
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
