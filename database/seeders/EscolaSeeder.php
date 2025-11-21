<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EscolaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_escola')->insert([
            ['nome' => 'Escola SP', 'cnpj' => '12345678000100', 'id_endereco' => 1, 'id_plano' => 1, 'status' => 'ativa', 'qtd_alunos' => 50],
            ['nome' => 'Escola RJ', 'cnpj' => '98765432000100', 'id_endereco' => 2, 'id_plano' => 2, 'status' => 'ativa', 'qtd_alunos' => 70],
        ]);
    }
}
