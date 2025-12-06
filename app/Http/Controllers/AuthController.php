<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // CRÍTICO: Usar o Facade Auth
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // Opcional: Adicionar middleware para rotas que precisam de proteção
        // $this->middleware('auth:sanctum')->except(['login']);
    }

    // Login unificado e mais seguro
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            // CRÍTICO R7: Mudança de 'senha' para 'password' para usar o Auth::attempt padrão do Laravel
            'password' => 'required|string', 
            'device_name' => 'required|string'
        ]);

        // 1. Tenta autenticar o usuário com as credenciais padrão do Laravel
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }
        
        // 2. Obtém o objeto User totalmente carregado
        $user = Auth::user();

        // 3. Verifica se o usuário está ativo (Regra de Negócio)
        if (!$user->ativo) {
             // Limpa a sessão criada pelo attempt
             Auth::logout(); 
             throw ValidationException::withMessages([
                'email' => ['Conta inativa. Contate o administrador.'],
            ]);
        }
        
        // CRÍTICO R3: Cria token. Usar findToken para evitar duplicação ou apenas createToken.
        // O token é criado com o nome do dispositivo.
        $token = $user->createToken($request->device_name)->plainTextToken;

        // CRÍTICO R3: Garante que a role seja acessível para a Policy e Front-end.
        // Assume que 'roles' é um relacionamento, mas o campo 'role' direto é mais eficiente para Policy.
        $role = $user->roles()->first()?->nome_role ?? 'aluno'; // Default para 'aluno' se não tiver role.
        
        // Complexidade de busca de senha e autenticação: O(1) com hash seguro.

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nome' => $user->name, // Assumindo 'name' no Model User
                    'email' => $user->email,
                    'role' => $role, // CRÍTICO: Usado pela PedidoPolicy
                    'id_escola' => $user->id_escola ?? null,
                    'id_cantina' => $user->id_cantina ?? null, // CRÍTICO: Usado pela PedidoPolicy
                    'ativo' => $user->ativo
                ]
            ]
        ]);
    }
    
    // Logout do token atual
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['data' => ['success' => true]]);
    }

    // Logout global (todos os tokens do usuário)
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['data' => ['success' => true]]);
    }

    // Refresh de token do dispositivo
    public function refresh(Request $request)
    {
        $user = $request->user();
        $device_name = $request->header('Device-Name') ?? 'unknown_device';
        
        // Remove token atual
        $request->user()->currentAccessToken()->delete();
        
        // Cria novo token
        $token = $user->createToken($device_name)->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token
            ]
        ]);
    }
}