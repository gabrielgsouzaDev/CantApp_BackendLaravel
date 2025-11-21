<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\CantinaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ItemPedidoController;
use App\Http\Controllers\CarteiraController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\ControleParentalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rotas organizadas: listagens públicas, ações que alteram dados sob
| auth:sanctum. Ajuste middleware conforme sua estratégia de autenticação.
|
*/

// -------- AUTH (public) --------
Route::post('/login', [AuthController::class, 'login']);

// logout precisa de autenticação
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// -------- PUBLIC READ ROUTES (listagens públicas) --------
// Users (leitura pública - se quiser proteger, mova para middleware)
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

// Escolas
Route::get('/escolas', [EscolaController::class, 'index']);
Route::get('/escolas/{id}', [EscolaController::class, 'show']);

// Cantinas
Route::get('/cantinas', [CantinaController::class, 'index']);
Route::get('/cantinas/{id}', [CantinaController::class, 'show']);
Route::get('/cantinas/escola/{id_escola}', [CantinaController::class, 'listarPorEscola']);
Route::get('/cantinas/{id}/produtos', [CantinaController::class, 'produtos']); // opcional, útil pro front

// Produtos
Route::get('/produtos', [ProdutoController::class, 'index']);
Route::get('/produtos/{id}', [ProdutoController::class, 'show']);

// Pedidos (consulta pública; se quiser proteger, mova para auth)
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'show']);

// Controle Parental (leitura)
Route::get('/controle-parental', [ControleParentalController::class, 'index']);
Route::get('/controle-parental/{id}', [ControleParentalController::class, 'show']);

// Carteira e Transações (leitura pública limitada — idealmente protegidas)
Route::get('/carteiras/{id}', [CarteiraController::class, 'show']);
Route::get('/transacoes', [TransacaoController::class, 'index']);
Route::get('/transacoes/{id}', [TransacaoController::class, 'show']);


// -------- PROTECTED ROUTES (requer auth:sanctum) --------
Route::middleware('auth:sanctum')->group(function () {

    // Users (criar/atualizar/deletar)
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Escolas (admin/gestor)
    Route::post('/escolas', [EscolaController::class, 'store']);
    Route::put('/escolas/{id}', [EscolaController::class, 'update']);
    Route::delete('/escolas/{id}', [EscolaController::class, 'destroy']);

    // Cantinas (gestor / cantineiro)
    Route::post('/cantinas', [CantinaController::class, 'store']);
    Route::put('/cantinas/{id}', [CantinaController::class, 'update']);
    Route::delete('/cantinas/{id}', [CantinaController::class, 'destroy']);

    // Produtos (cantina)
    Route::post('/produtos', [ProdutoController::class, 'store']);
    Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);

    // Pedidos (criar, atualizar status, deletar)
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::put('/pedidos/{id}', [PedidoController::class, 'update']); // atualização geral
    Route::put('/pedidos/{id}/status', [PedidoController::class, 'updateStatus']); // só status
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);

    // Itens de Pedido (opcional manipulação direta)
    Route::post('/item-pedidos', [ItemPedidoController::class, 'store']);
    Route::put('/item-pedidos/{id}', [ItemPedidoController::class, 'update']);
    Route::delete('/item-pedidos/{id}', [ItemPedidoController::class, 'destroy']);

    // Carteira & Transacao
    Route::post('/carteiras', [CarteiraController::class, 'store']);
    Route::put('/carteiras/{id}', [CarteiraController::class, 'update']);
    Route::post('/transacoes', [TransacaoController::class, 'store']);
    Route::put('/transacoes/{id}', [TransacaoController::class, 'update']);
    Route::delete('/transacoes/{id}', [TransacaoController::class, 'destroy']);

    // Controle Parental
    Route::post('/controle-parental', [ControleParentalController::class, 'store']);
    Route::put('/controle-parental/{id}', [ControleParentalController::class, 'update']);
    Route::delete('/controle-parental/{id}', [ControleParentalController::class, 'destroy']);
});
