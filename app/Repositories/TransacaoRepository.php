<?php

namespace App\Repositories;

use App\Models\Transacao;

class TransacaoRepository
{
    public function all()
    {
        return Transacao::all();
    }

    public function find(int $id): ?Transacao
    {
        return Transacao::find($id);
    }

    public function create(array $data): Transacao
    {
        return Transacao::create($data);
    }

    public function update(int $id, array $data): ?Transacao
    {
        $transacao = Transacao::find($id);
        if (!$transacao) return null;

        $transacao->update($data);
        return $transacao;
    }

    public function delete(int $id): bool
    {
        $transacao = Transacao::find($id);
        if (!$transacao) return false;

        return $transacao->delete();
    }
}
