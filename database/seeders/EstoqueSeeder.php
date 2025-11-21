<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstoqueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_estoque')->insert([
            ['id_produto' => 1, 'quantidade' => 50],
            ['id_produto' => 2, 'quantidade' => 30],
            ['id_produto' => 3, 'quantidade' => 40],
            ['id_produto' => 4, 'quantidade' => 60],
        ]);
    }
}
