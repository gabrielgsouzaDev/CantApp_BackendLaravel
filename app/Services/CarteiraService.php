<?php

namespace App\Services;

use App\Repositories\CarteiraRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CarteiraService
{
    protected $repository;

    public function __construct(CarteiraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function debit(int $userId, float $amount)
    {
        return DB::transaction(function () use ($userId, $amount) {
            $carteira = $this->repository->findByUserId($userId);
            if (!$carteira) throw new Exception("Carteira do usuário não encontrada");
            if ($carteira->saldo < $amount) throw new Exception("Saldo insuficiente");
            $carteira->saldo -= $amount;
            $carteira->save();
            return $carteira;
        });
    }

    public function credit(int $userId, float $amount)
    {
        return DB::transaction(function () use ($userId, $amount) {
            $carteira = $this->repository->findByUserId($userId);
            if (!$carteira) throw new Exception("Carteira do usuário não encontrada");
            $carteira->saldo += $amount;
            $carteira->save();
            return $carteira;
        });
    }
}
