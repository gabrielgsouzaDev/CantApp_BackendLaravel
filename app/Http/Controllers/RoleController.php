<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;

class RoleController extends Controller
{
    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_role' => 'required|string|unique:tb_role,nome_role'
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
