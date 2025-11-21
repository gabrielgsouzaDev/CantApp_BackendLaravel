<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserRoleService;

class UserRoleController extends Controller
{
    protected $service;

    public function __construct(UserRoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer|exists:users,id',
            'id_role' => 'required|integer|exists:tb_role,id_role'
        ]);

        return response()->json($this->service->assignRole($data));
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer|exists:users,id',
            'id_role' => 'required|integer|exists:tb_role,id_role'
        ]);

        return response()->json(['deleted' => $this->service->removeRole($data['id_user'], $data['id_role'])]);
    }
}
