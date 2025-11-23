<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        Produto::insert([
    [
        'id_cantina' => 1,
        'nome' => 'Suco de Laranja',
        'preco' => 5.50,
        'ativo' => true,
        'categoria' => 'Bebida',
        'url_imagem' => 'https://meuservidor.com/imagens/suco.jpg'
    ],
    [
        'id_cantina' => 1,
        'nome' => 'Sanduíche Natural',
        'preco' => 8.00,
        'ativo' => true,
        'categoria' => 'Almoço',
        'url_imagem' => 'https://meuservidor.com/imagens/sanduiche.jpg'
    ],
    [
        'id_cantina' => 2,
        'nome' => 'Refrigerante',
        'preco' => 4.50,
        'ativo' => true,
        'categoria' => 'Bebida',
        'url_imagem' => 'https://meuservidor.com/imagens/refri.jpg'
    ]
]);

    }
}
