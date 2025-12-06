<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash; // CRÍTICO: Usar Facade Hash
use App\Models\User;
use App\Models\Role; // CRÍTICO: Importar Role
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar todos os usuários (Admin/Escola)
     */
    public function index()
    {
        // CRÍTICO R3: Este método deve ser protegido por Policy/Gate para admins
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

        // CRÍTICO R3: A Policy deve garantir que apenas o próprio usuário ou Admin/Gestor possa ver
        // Ex: $this->authorize('view', $user);
        
        // Carrega relacionamentos essenciais sem quebrar se forem nulos
        // R4: Otimização: Usar Eager Loading em Service/Repository
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
     * Criar novo usuário (Endpoint de Registro)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            // CRÍTICO R17: Validação corrigida de 'senha' para 'password'
            'password' => 'required|string|min:6', 
            'id_escola' => 'nullable|integer',
            'id_cantina' => 'nullable|integer',
            'ativo' => 'boolean',
            // Valida que a 'role' é uma string existente na tabela de roles
            'role' => 'required|string|exists:tb_role,nome_role' 
        ]);

        // 1. Hash da senha (Usar Hash Facade)
        $data['senha_hash'] = Hash::make($data['password']);

        $roleName = $data['role']; // guarda o nome da role
        
        // 2. Remove campos que não são colunas do DB
        unset($data['password'], $data['role']); 
        
        // 3. Cria o usuário no DB
        $user = $this->service->create($data); // Assumindo que o Service usa $data['senha_hash']

        // 4. Associa a role
        if ($user) {
            // CRÍTICO R7: Ajustado para usar App\Models\Role::class
            $role = Role::where('nome_role', $roleName)->first();
            if ($role) {
                // Assumindo que o relacionamento roles está corretamente definido
                $user->roles()->attach($role->id_role); 
            }
        }

        // Carrega o usuário com a role associada para o retorno
        $user->load('roles');

        return response()->json(['data' => $user], 201);
    }


    /**
     * Atualizar usuário
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        // R3: Policy de Autorização deve ser aplicada aqui: $this->authorize('update', $this->service->find($id));
        
        // Se enviar senha, gerar hash
        if (isset($data['senha'])) {
            $data['senha_hash'] = Hash::make($data['senha']);
            unset($data['senha']);
        }
        
        // R3: Garantir que o usuário não possa mudar campos críticos (role, ativo) se não for Admin
        if (!Auth::user()->isAdmin() && (isset($data['role']) || isset($data['ativo']))) {
            // Opção 1: Remover campos não permitidos
            unset($data['role'], $data['ativo']); 
            // Opção 2: Aplicar Policy/FormRequest
        }

        $user = $this->service->update($id, $data);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['data' => $user->fresh()]);
    }

    /**
     * Deletar usuário
     */
    public function destroy($id)
    {
        // R3: Policy de Autorização deve ser aplicada aqui: $this->authorize('delete', $this->service->find($id));
        
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }
}