<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Login unificado
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
            'device_name' => 'required|string' // para diferenciar tokens por dispositivo
        ]);

        $user = $this->userService->all()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->senha, $user->senha_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        // Cria token único por dispositivo
        $token = $user->createToken($request->device_name)->plainTextToken;

        // Role principal do usuário
        $role = $user->roles()->first()?->nome_role ?? 'Sem role';

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nome' => $user->nome,
                    'email' => $user->email,
                    'role' => $role,
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

        // Remove token atual e cria novo
        $request->user()->currentAccessToken()->delete();
        $token = $user->createToken($device_name)->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token
            ]
        ]);
    }
}
