<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar todos os usuários
     */
    public function index()
    {
        $users = $this->service->all();
        return response()->json(['data' => $users]);
    }

    /**
     * Mostrar um usuário específico
     */
    public function show($id)
    {
        $user = $this->service->find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Carrega relacionamentos essenciais sem quebrar se forem nulos
        $user->load([
            'roles',
            'dependentes',
            'responsaveis',
            'escola',
            'cantina',
            'carteira',
            'controleParentalComoResponsavel',
            'controleParentalComoAluno',
            'pedidosFeitos',
            'pedidosRecebidos'
        ]);

        // Resposta consistente para o frontend
        return response()->json(['data' => $user]);
    }

    /**
     * Criar novo usuário
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'senha' => 'required|string|min:6',
            'id_escola' => 'nullable|integer',
            'id_cantina' => 'nullable|integer',
            'ativo' => 'boolean'
        ]);

        // Criptografar senha
        $data['senha_hash'] = \Hash::make($data['senha']);
        unset($data['senha']);

        $user = $this->service->create($data);

        return response()->json(['data' => $user], 201);
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        // Se enviar senha, gerar hash
        if (isset($data['senha'])) {
            $data['senha_hash'] = \Hash::make($data['senha']);
            unset($data['senha']);
        }

        $user = $this->service->update($id, $data);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['data' => $user]);
    }

    /**
     * Deletar usuário
     */
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}
