<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Escola;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    // =============================
    // ESCOLAS — Ações do Admin
    // =============================

    public function listarPendentes()
    {
        $pendentes = Escola::where('status', 'pendente')->get();

        return ResponseHelper::success($pendentes);
    }

    public function aprovar($id)
    {
        $escola = Escola::findOrFail($id);
        $escola->status = 'ativa';   // usa enum existente
        $escola->save();

        return ResponseHelper::success([
            'message' => 'Escola aprovada com sucesso!'
        ]);
    }

    public function negar($id)
    {
        $escola = Escola::findOrFail($id);
        $escola->status = 'inativa';  // usa enum existente
        $escola->save();

        return ResponseHelper::success([
            'message' => 'Escola negada com sucesso!'
        ]);
    }

    // =============================
    // ADMINS — Login e CRUD
    // =============================

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $result = $this->adminService->login($request->email, $request->senha);

        return ResponseHelper::success($result);
    }

    public function index()
    {
        $admins = $this->adminService->listarTodos();
        return ResponseHelper::success($admins);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:admins,email',
            'senha' => 'required|min:6'
        ]);

        $admin = $this->adminService->criarAdmin($request->all());

        return ResponseHelper::success($admin, 201);
    }
}
