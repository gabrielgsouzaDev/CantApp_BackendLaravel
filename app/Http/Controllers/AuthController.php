<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // NOTA: O middleware de autenticação deve estar no routes/api.php,
        // mas você pode usá-lo aqui para proteger métodos específicos se necessário.
    }

    /**
     * Login unificado e mais seguro.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            // O Front-end envia 'password', o que está correto para o Auth::attempt padrão do Laravel
            'password' => 'required|string', 
            'device_name' => 'required|string'
        ]);

        // 1. Tenta autenticar o usuário
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }
        
        // 2. Obtém o objeto User
        $user = Auth::user();

        // 3. Verifica se o usuário está ativo (Regra de Negócio)
        if (!$user->ativo) {
            // Limpa a sessão criada pelo attempt
            Auth::logout(); 
            throw ValidationException::withMessages([
                'email' => ['Conta inativa. Contate o administrador.'],
            ]);
        }
        
        // 4. Cria token.
        $token = $user->createToken($request->device_name)->plainTextToken;

        // 5. Determina o Role do usuário
        // Usa o accessor 'role' se estiver implementado, ou a primeira role do relacionamento
        $role = $user->roles()->first()?->nome_role ?? 'aluno'; 
        
        // 6. Retorna o Payload de Resposta
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    // CRÍTICO R58: Corrigido o campo de $user->name (nulo/ausente) para $user->nome,
                    // que é o campo real no banco de dados. Isso resolve o R55 no Front-end.
                    'nome' => $user->nome, 
                    'email' => $user->email,
                    'role' => $role,
                    'id_escola' => $user->id_escola ?? null,
                    'id_cantina' => $user->id_cantina ?? null,
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