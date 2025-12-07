<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PedidoPolicy
{
    use HandlesAuthorization;

    /**
     * Helper para ler o role de forma segura e em minúsculas (R33).
     */
    protected function getUserRole(User $user): string
    {
        // Tenta ler o role do relacionamento ou da propriedade 'role' (se existir)
        $role = $user->role ?? $user->roles?->first()?->nome_role;
        return strtolower($role ?? 'guest');
    }

    /**
     * CRÍTICO R3: Permite que o Super-Admin (Admin) ignore as verificações da Policy.
     */
    public function before(User $user): ?bool
    {
        if ($this->getUserRole($user) === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Determina se o usuário pode listar os pedidos (viewAny).
     */
    public function viewAny(User $user): bool
    {
        $role = $this->getUserRole($user);
        return in_array($role, ['aluno', 'cantineiro', 'responsavel']);
    }

    /**
     * Determina se o usuário pode visualizar um Pedido específico.
     */
    public function view(User $user, Pedido $pedido): Response
    {
        $role = $this->getUserRole($user);

        // 1. Regra do Cliente (Propriedade)
        if (in_array($role, ['aluno', 'responsavel'])) {
            $isOwner = $user->id === $pedido->id_comprador || $user->id === $pedido->id_destinatario;
            return $isOwner
                ? Response::allow()
                : Response::deny('Você não tem permissão para visualizar este pedido.');
        }

        // 2. Regra do Cantineiro (Hierarquia)
        if ($role === 'cantineiro') {
            $isAssociatedCanteen = $user->id_cantina === $pedido->id_cantina;
            return $isAssociatedCanteen
                ? Response::allow()
                : Response::deny('Este pedido não pertence à sua cantina.');
        }

        return Response::deny('Acesso negado para este tipo de usuário.');
    }

    /**
     * Determina se o usuário pode criar pedidos.
     */
    public function create(User $user): bool
    {
        $role = $this->getUserRole($user);
        // Apenas Alunos e Responsáveis podem criar pedidos (comprar).
        return in_array($role, ['aluno', 'responsavel']);
    }

    /**
     * Determina se o usuário pode atualizar o Pedido (ex: mudar status).
     */
    public function update(User $user, Pedido $pedido): Response
    {
        $role = $this->getUserRole($user);

        // Apenas Cantineiros podem atualizar pedidos (mudar status).
        if ($role === 'cantineiro') {
            $isAssociatedCanteen = $user->id_cantina === $pedido->id_cantina;
            
            return $isAssociatedCanteen
                ? Response::allow()
                : Response::deny('Você só pode atualizar pedidos da sua cantina.');
        }

        return Response::deny('Você não tem permissão para alterar este pedido.');
    }

    /**
     * Determina se o usuário pode deletar/cancelar o modelo.
     */
    public function delete(User $user, Pedido $pedido): Response
    {
        $role = $this->getUserRole($user);

        // Permite o cancelamento APENAS se for o comprador E o status for 'pendente'
        if ($role === 'aluno' && $user->id === $pedido->id_comprador) {
            return $pedido->status === 'pendente'
                ? Response::allow()
                : Response::deny('Só é possível cancelar pedidos com status pendente.');
        }
        
        return Response::deny('Acesso negado.');
    }
    
    // Métodos restore e forceDelete mantidos com 'return false;' para restrição máxima.
    public function restore(User $user, Pedido $pedido): bool { return false; }
    public function forceDelete(User $user, Pedido $pedido): bool { return false; }
}