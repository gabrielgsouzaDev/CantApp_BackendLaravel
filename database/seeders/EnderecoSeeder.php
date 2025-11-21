<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Endereco;

class EnderecoSeeder extends Seeder
{
    public function run()
    {
        Endereco::insert([
            ['cep' => '12345-678', 'logradouro' => 'Rua A', 'numero' => '100', 'complemento' => '', 'bairro' => 'Centro', 'cidade' => 'Cidade A', 'estado' => 'SP'],
            ['cep' => '87654-321', 'logradouro' => 'Rua B', 'numero' => '200', 'complemento' => 'Bloco 1', 'bairro' => 'Bairro B', 'cidade' => 'Cidade B', 'estado' => 'RJ'],
        ]);
    }
}
