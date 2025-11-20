<?php

namespace App\Repositories;

use App\Models\Aluno;

class AlunoRepository
{
    public function all()
    {
        return Aluno::all();
    }

    public function find(int $id): ?Aluno
    {
        return Aluno::find($id);
    }

    public function findByEmail(string $email): ?Aluno
    {
        return Aluno::where('email', $email)->first();
    }

    public function create(array $data): Aluno
    {
        return Aluno::create($data);
    }

    public function update(int $id, array $data): ?Aluno
    {
        $aluno = Aluno::find($id);

        if (!$aluno) {
            return null;
        }

        $aluno->update($data);

        return $aluno;
    }

    public function delete(int $id): bool
    {
        return Aluno::destroy($id) > 0;
    }
}
