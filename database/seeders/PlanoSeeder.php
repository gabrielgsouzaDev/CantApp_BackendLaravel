<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_plano')->insert([
            ['nome' => 'Plano Básico', 'preco_mensal' => 100.00, 'qtd_max_alunos' => 100, 'qtd_max_cantinas' => 2],
            ['nome' => 'Plano Premium', 'preco_mensal' => 200.00, 'qtd_max_alunos' => 200, 'qtd_max_cantinas' => 5],
        ]);
    }
}
