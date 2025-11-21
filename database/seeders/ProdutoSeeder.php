<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_produto')->insert([
            ['id_cantina' => 1, 'nome' => 'Suco de Laranja', 'preco' => 5.50, 'ativo' => true],
            ['id_cantina' => 1, 'nome' => 'Sanduíche Natural', 'preco' => 10.00, 'ativo' => true],
            ['id_cantina' => 2, 'nome' => 'Refrigerante Lata', 'preco' => 6.00, 'ativo' => true],
            ['id_cantina' => 2, 'nome' => 'Coxinha', 'preco' => 4.50, 'ativo' => true],
        ]);
    }
}
