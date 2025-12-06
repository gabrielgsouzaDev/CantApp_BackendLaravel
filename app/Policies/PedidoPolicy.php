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
     * CRÍTICO R3: Permite que o Super-Admin (Admin) ignore as verificações da Policy.
     * Assume que 'admin' é a role mais alta.
     */
    public function before(User $user): ?bool
    {
        // Se o usuário for Admin, bypass todas as regras de autorização.
        if ($user->role === 'admin') {
            return true;
        }

        return null; // Continuar com as verificações padrão da Policy
    }

    /**
     * Determina se o usuário pode listar os pedidos (viewAny).
     * Cantineiros e Alunos devem poder listar. O filtro é aplicado no Controller/Query.
     */
    public function viewAny(User $user): bool
    {
        // Se for Cantineiro, Aluno, ou Responsável, a listagem é permitida (true).
        return in_array($user->role, ['aluno', 'cantineiro', 'responsavel']);
    }

    /**
     * Determina se o usuário pode visualizar um Pedido específico.
     * CRÍTICO R3: Checagem de propriedade e hierarquia.
     */
    public function view(User $user, Pedido $pedido): Response
    {
        // 1. Regra do Aluno (Propriedade)
        if (in_array($user->role, ['aluno', 'responsavel'])) {
            $isOwner = $user->id === $pedido->id_comprador || $user->id === $pedido->id_destinatario;
            
            return $isOwner
                ? Response::allow()
                : Response::deny('Você não tem permissão para visualizar este pedido.');
        }

        // 2. Regra do Cantineiro (Hierarquia)
        if ($user->role === 'cantineiro') {
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
        // Apenas Alunos e Responsáveis podem criar pedidos (comprar).
        return in_array($user->role, ['aluno', 'responsavel']);
    }

    /**
     * Determina se o usuário pode atualizar o Pedido (ex: mudar status).
     * CRÍTICO R3: Apenas o Cantineiro pode mudar o status (updateStatus).
     */
    public function update(User $user, Pedido $pedido): Response
    {
        // Apenas Cantineiros podem atualizar pedidos (mudar status).
        if ($user->role === 'cantineiro') {
            $isAssociatedCanteen = $user->id_cantina === $pedido->id_cantina;
            
            return $isAssociatedCanteen
                ? Response::allow()
                : Response::deny('Você só pode atualizar pedidos da sua cantina.');
        }

        // Alunos e outros não podem usar este método.
        return Response::deny('Você não tem permissão para alterar este pedido.');
    }

    /**
     * Determina se o usuário pode deletar o modelo.
     * CRÍTICO R3: Esta permissão deve ser extremamente restrita.
     */
    public function delete(User $user, Pedido $pedido): Response
    {
        // Permite o cancelamento APENAS se for o comprador E o status for 'pendente'
        if ($user->role === 'aluno' && $user->id === $pedido->id_comprador) {
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