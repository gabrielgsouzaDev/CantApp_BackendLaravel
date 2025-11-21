<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanoSeeder extends Seeder
{
    public function run()
    {
        Plano::insert([
            ['nome' => 'Plano Básico', 'preco_mensal' => 150.00, 'qtd_max_alunos' => 500, 'qtd_max_cantinas' => 1],
            ['nome' => 'Plano Avançado', 'preco_mensal' => 300.00, 'qtd_max_alunos' => 1000, 'qtd_max_cantinas' => 2],
        ]);
    }
}
