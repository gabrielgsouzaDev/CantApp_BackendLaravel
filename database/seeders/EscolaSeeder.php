<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Escola;

class EscolaSeeder extends Seeder
{
    public function run()
    {
        Escola::insert([
            ['nome' => 'Escola Municipal Central', 'cnpj' => '12.345.678/0001-99', 'id_endereco' => 1, 'id_plano' => 1, 'status' => 'ativo', 'qtd_alunos' => 500],
            ['nome' => 'Escola Estadual Nova', 'cnpj' => '98.765.432/0001-55', 'id_endereco' => 2, 'id_plano' => 2, 'status' => 'ativo', 'qtd_alunos' => 800],
        ]);
    }
}
