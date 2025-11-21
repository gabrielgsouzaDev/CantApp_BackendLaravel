<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with([
            'roles', 
            'escola', 
            'cantina', 
            'carteira', 
            'transacoesFeitas', 
            'transacoesAprovadas', 
            'dependentes', 
            'responsaveis', 
            'controleParentalComoResponsavel', 
            'controleParentalComoAluno', 
            'pedidosFeitos', 
            'pedidosRecebidos'
        ])->get();
    }

    public function find($id)
    {
        return $this->model->with([
            'roles', 
            'escola', 
            'cantina', 
            'carteira', 
            'transacoesFeitas', 
            'transacoesAprovadas', 
            'dependentes', 
            'responsaveis', 
            'controleParentalComoResponsavel', 
            'controleParentalComoAluno', 
            'pedidosFeitos', 
            'pedidosRecebidos'
        ])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
