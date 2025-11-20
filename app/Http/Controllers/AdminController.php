<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

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
