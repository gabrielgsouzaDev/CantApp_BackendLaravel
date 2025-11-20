<?php

namespace App\Repositories;

use App\Models\Pedido;

class PedidoRepository
{
    public function all()
    {
        return Pedido::with(['aluno', 'responsavel', 'produtos'])->get();
    }

    public function find(int $id): ?Pedido
    {
        return Pedido::with(['aluno', 'responsavel', 'produtos'])->find($id);
    }

    public function create(array $data): Pedido
    {
        $pedido = Pedido::create([
            'aluno_id' => $data['aluno_id'] ?? null,
            'responsavel_id' => $data['responsavel_id'] ?? null,
            'status' => $data['status'] ?? 'pendente',
            'valor_total' => 0
        ]);

        $total = 0;

        foreach ($data['produtos'] as $p) {
            $produto = $p['produto']; // Aqui o Service já passa o produto correto
            $subtotal = $produto->preco * $p['quantidade'];
            $total += $subtotal;

            $pedido->produtos()->attach($produto->id, [
                'quantidade' => $p['quantidade'],
                'subtotal' => $subtotal
            ]);
        }

        $pedido->update(['valor_total' => $total]);

        return $pedido->load(['produtos']);
    }

    public function update(int $id, array $data): bool
    {
        $pedido = Pedido::findOrFail($id);
        return $pedido->update($data);
    }

    public function delete(int $id): bool
    {
        return Pedido::destroy($id);
    }

    public function findByDestinatario(string $tipo, int $id)
    {
        if ($tipo === 'aluno') {
            return Pedido::where('aluno_id', $id)->get();
        } elseif ($tipo === 'responsavel') {
            return Pedido::where('responsavel_id', $id)->get();
        }
        return collect([]);
    }
}
