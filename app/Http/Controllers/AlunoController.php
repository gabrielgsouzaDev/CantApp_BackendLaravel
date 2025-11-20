<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class AlunoController extends Controller
{
    protected $alunoService;

    public function __construct(AlunoService $alunoService)
    {
        $this->alunoService = $alunoService;
    }

    public function index()
    {
        return ResponseHelper::success(
            $this->alunoService->listar()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:alunos,email',
            'senha' => 'required|min:6',
            'data_nascimento' => 'required|date'
        ]);

        return ResponseHelper::success(
            $this->alunoService->criar($request->all()),
            201
        );
    }

    public function show($id)
    {
        $aluno = $this->alunoService->listar()->find($id);

        if (!$aluno) {
            return ResponseHelper::error("Aluno não encontrado", 404);
        }

        return ResponseHelper::success($aluno);
    }

    public function update(Request $request, $id)
    {
        $aluno = $this->alunoService->atualizar($id, $request->all());

        if (!$aluno) {
            return ResponseHelper::error("Aluno não encontrado", 404);
        }

        return ResponseHelper::success($aluno);
    }

    public function destroy($id)
    {
        $excluido = $this->alunoService->deletar($id);

        if (!$excluido) {
            return ResponseHelper::error("Aluno não encontrado", 404);
        }

        return ResponseHelper::success("Aluno removido");
    }
}
