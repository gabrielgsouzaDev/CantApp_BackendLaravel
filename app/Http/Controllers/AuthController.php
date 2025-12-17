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
    // CÓDIGO TEMPORÁRIO PARA DEBUG
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string', 
            'device_name' => 'required|string'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages(['email' => ['Credenciais inválidas.']]);
        }
        
        $user = Auth::user();
        
        // ... O restante da sua lógica
        
        // Retorno de sucesso (o try-catch só será executado se houver uma exceção antes desta linha)
        return response()->json([ /* ... */ ]);
        
    } catch (\Exception $e) {
        // ESSA LINHA É CRÍTICA PARA O DEBUG NO RAILWAY
        return response()->json([
            'error' => 'Falha Interna Crítica',
            'exception_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
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