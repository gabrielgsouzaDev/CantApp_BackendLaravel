<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($id)
    {
        $user = $this->service->find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = $this->service->create($data);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $updated = $this->service->update($id, $request->all());

        if (!$updated) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['message' => 'Atualizado com sucesso']);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['message' => 'Removido']);
    }
}
