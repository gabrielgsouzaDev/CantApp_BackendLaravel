<?php

namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;
use Exception;

class AdminService
{
    protected $repo;

    public function __construct(AdminRepository $repo)
    {
        $this->repo = $repo;
    }

    /** LOGIN */
    public function login(string $email, string $senha)
    {
        $admin = $this->repo->findByEmail($email);

        if (!$admin || !Hash::check($senha, $admin->senha_hash)) {
            throw new Exception('Credenciais inválidas');
        }

        return $admin;
    }

    /** LISTAR TODOS */
    public function listarTodos()
    {
        return $this->repo->all();
    }

    /** CRIAR ADMIN */
    public function criarAdmin(array $dados)
    {
        $dados['senha_hash'] = Hash::make($dados['senha']);
        unset($dados['senha']);

        return $this->repo->create($dados);
    }

    /** ATUALIZAR ADMIN */
    public function atualizarAdmin(int $id, array $dados)
    {
        if (isset($dados['senha'])) {
            $dados['senha_hash'] = Hash::make($dados['senha']);
            unset($dados['senha']);
        }

        return $this->repo->update($id, $dados);
    }

    /** DELETAR */
    public function deletarAdmin(int $id)
    {
        return $this->repo->delete($id);
    }
}
