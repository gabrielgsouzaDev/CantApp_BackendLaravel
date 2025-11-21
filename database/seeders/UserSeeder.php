<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'nome' => 'Aluno Exemplo',
                'email' => 'aluno@escola.com',
                'telefone' => '11999999999',
                'data_nascimento' => '2010-05-15',
                'senha_hash' => Hash::make('senha123'),
                'id_escola' => 1,
                'id_cantina' => null,
                'ativo' => true
            ],
            [
                'nome' => 'Responsavel Exemplo',
                'email' => 'responsavel@escola.com',
                'telefone' => '11988888888',
                'data_nascimento' => '1980-02-20',
                'senha_hash' => Hash::make('senha123'),
                'id_escola' => null,
                'id_cantina' => null,
                'ativo' => true
            ],
            [
                'nome' => 'Admin Exemplo',
                'email' => 'admin@escola.com',
                'telefone' => '11977777777',
                'data_nascimento' => '1990-01-01',
                'senha_hash' => Hash::make('senha123'),
                'id_escola' => null,
                'id_cantina' => null,
                'ativo' => true
            ],
        ]);
    }
}
