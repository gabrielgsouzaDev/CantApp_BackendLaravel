<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carteira;

class CarteiraSeeder extends Seeder
{
    public function run()
    {
        Carteira::insert([
            [
                'id_user' => 1,      // dono da carteira (Aluno)
                'saldo' => 0.00,     // carteira inicial zerada
            ],
            [
                'id_user' => 2,      // dono da carteira (Responsável)
                'saldo' => 100.00,   // carteira com saldo
            ],
            [
                'id_user' => 3,      // dono da carteira (Admin)
                'saldo' => 0.00,
            ]
        ]);
    }
}
