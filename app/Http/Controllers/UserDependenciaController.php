<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserDependenciaService;

class UserDependenciaController extends Controller
{
    protected $service;

    public function __construct(UserDependenciaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($responsavelId, $dependenteId)
    {
        return response()->json($this->service->find($responsavelId, $dependenteId));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_responsavel' => 'required|integer',
            'id_dependente' => 'required|integer'
        ]);

        return response()->json($this->service->create($data));
    }

    public function destroy($responsavelId, $dependenteId)
    {
        return response()->json(['deleted' => $this->service->delete($responsavelId, $dependenteId)]);
    }
}
