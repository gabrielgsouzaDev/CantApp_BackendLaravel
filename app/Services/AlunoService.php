<?php

namespace App\Services;

use App\Repositories\AlunoRepository;
use Illuminate\Support\Facades\Hash;
use Exception;

class AlunoService
{
    protected $alunoRepo;

    public function __construct(AlunoRepository $alunoRepo)
    {
        $this->alunoRepo = $alunoRepo;
    }

    public function listar()
    {
        return $this->alunoRepo->all();
    }

    public function criar(array $dados)
    {
        $dados['senha_hash'] = Hash::make($dados['senha']);
        unset($dados['senha']);

        return $this->alunoRepo->create($dados);
    }

    public function atualizar(int $id, array $dados)
    {
        // Hash da senha se enviada
        if (isset($dados['senha'])) {
            $dados['senha_hash'] = Hash::make($dados['senha']);
            unset($dados['senha']);
        }

        return $this->alunoRepo->update($id, $dados);
    }

    public function deletar(int $id)
    {
        return $this->alunoRepo->delete($id);
    }
}
