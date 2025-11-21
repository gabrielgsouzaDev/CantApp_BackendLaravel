<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnderecoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_endereco')->insert([
            ['cep' => '01001-000', 'logradouro' => 'Rua Exemplo SP', 'cidade' => 'São Paulo', 'estado' => 'SP'],
            ['cep' => '20010-000', 'logradouro' => 'Rua Exemplo RJ', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ'],
        ]);
    }
}
