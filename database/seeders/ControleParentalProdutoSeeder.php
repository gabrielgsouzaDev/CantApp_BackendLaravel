<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ControleParentalProduto;

class ControleParentalProdutoSeeder extends Seeder
{
    public function run()
    {
        ControleParentalProduto::insert([
            ['id_controle' => 1, 'id_produto' => 1, 'permitido' => true],
            ['id_controle' => 1, 'id_produto' => 2, 'permitido' => false],
            ['id_controle' => 1, 'id_produto' => 3, 'permitido' => true],
        ]);
    }
}
