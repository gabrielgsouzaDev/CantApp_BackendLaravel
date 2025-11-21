<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\CantinaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// -------- ADMIN --------
Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admins',      [AdminController::class, 'index']);
Route::post('/admins',     [AdminController::class, 'store']);

// -------- ALUNOS --------
Route::get('/alunos',          [AlunoController::class, 'index']);
Route::post('/alunos',         [AlunoController::class, 'store']);
Route::get('/alunos/{id}',     [AlunoController::class, 'show']);
Route::put('/alunos/{id}',     [AlunoController::class, 'update']);
Route::delete('/alunos/{id}',  [AlunoController::class, 'destroy']);

// -------- RESPONSÁVEIS --------
Route::get('/responsaveis',          [ResponsavelController::class, 'index']);
Route::post('/responsaveis',         [ResponsavelController::class, 'store']);
Route::get('/responsaveis/{id}',     [ResponsavelController::class, 'show']);
Route::put('/responsaveis/{id}',     [ResponsavelController::class, 'update']);
Route::delete('/responsaveis/{id}',  [ResponsavelController::class, 'destroy']);

// -------- PRODUTOS --------
Route::get('/produtos',         [ProdutoController::class, 'index']);
Route::post('/produtos',        [ProdutoController::class, 'store']);
Route::get('/produtos/{id}',    [ProdutoController::class, 'show']);
Route::put('/produtos/{id}',    [ProdutoController::class, 'update']);
Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);

// -------- PEDIDOS --------
Route::get('/pedidos',              [PedidoController::class, 'index']);
Route::post('/pedidos',             [PedidoController::class, 'store']);
Route::put('/pedidos/{id}/status',  [PedidoController::class, 'updateStatus']);

// -------- ESCOLAS --------
Route::get('/escolas',        [EscolaController::class, 'index']);
Route::post('/escolas',       [EscolaController::class, 'store']);
Route::get('/escolas/{id}',   [EscolaController::class, 'show']);
Route::put('/escolas/{id}',   [EscolaController::class, 'update']);
Route::delete('/escolas/{id}',[EscolaController::class, 'destroy']);

// -------- CANTINAS --------
Route::get('/cantinas',                      [CantinaController::class, 'index']);
Route::get('/cantinas/escola/{id_escola}',  [CantinaController::class, 'listarPorEscola']);
Route::get('/cantinas/{id}',                [CantinaController::class, 'show']);
