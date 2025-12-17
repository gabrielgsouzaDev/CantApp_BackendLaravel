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
    // 1. Validação (Se falhar, o Laravel lança 422 automaticamente)
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string', 
        'device_name' => 'required|string'
    ]);

    // 2. Tentativa de Autenticação
    if (!Auth::attempt($request->only('email', 'password'))) {
        // Se a senha estiver errada, cairá aqui com 422.
        return response()->json([
            'message' => 'Credenciais inválidas.',
            'errors' => ['email' => ['O e-mail ou senha inseridos estão incorretos.']]
        ], 422);
    }
    
    $user = Auth::user();

    if (!$user->ativo) {
        Auth::logout(); 
        return response()->json(['message' => 'Conta inativa.'], 403);
    }
    
    // 3. Sucesso: Geração de Token e Mapeamento
    $token = $user->createToken($request->device_name)->plainTextToken;
    $role = $user->roles()->first()?->nome_role ?? 'aluno'; 

    return response()->json([
        'success' => true,
        'data' => [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nome' => $user->nome, // R58: Garante o nome correto
                'email' => $user->email,
                'role' => strtolower($role),
                'id_escola' => $user->id_escola,
                'id_cantina' => $user->id_cantina,
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