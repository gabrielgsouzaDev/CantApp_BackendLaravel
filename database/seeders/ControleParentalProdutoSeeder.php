<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ControleParentalProdutoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_controle_parental_produto')->insert([
            ['id_controle' => 1, 'id_produto' => 1, 'permitido' => true],
            ['id_controle' => 1, 'id_produto' => 2, 'permitido' => false],
            ['id_controle' => 2, 'id_produto' => 3, 'permitido' => true],
            ['id_controle' => 2, 'id_produto' => 4, 'permitido' => true],
        ]);
    }
}
