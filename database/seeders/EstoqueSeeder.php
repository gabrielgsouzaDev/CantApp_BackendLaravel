<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estoque;

class EstoqueSeeder extends Seeder
{
    public function run()
    {
        Estoque::insert([
            ['id_produto' => 1, 'quantidade' => 50],
            ['id_produto' => 2, 'quantidade' => 30],
            ['id_produto' => 3, 'quantidade' => 20],
        ]);
    }
}
