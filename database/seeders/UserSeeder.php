<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nome' => 'Admin Teste',
                'email' => 'admin@teste.com',
                'senha_hash' => Hash::make('admin123'),
                'ativo' => true,
                'id_escola' => null
            ],
            [
                'nome' => 'Gestor SP',
                'email' => 'gestor.sp@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => 1
            ],
            [
                'nome' => 'Gestor RJ',
                'email' => 'gestor.rj@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => 2
            ],
            [
                'nome' => 'Responsavel João',
                'email' => 'resp.joao@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => null
            ],
            [
                'nome' => 'Responsavel Maria',
                'email' => 'resp.maria@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => null
            ],
            [
                'nome' => 'Aluno Pedro',
                'email' => 'aluno.pedro@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => 1
            ],
            [
                'nome' => 'Aluno Ana',
                'email' => 'aluno.ana@teste.com',
                'senha_hash' => Hash::make('senha123'),
                'ativo' => true,
                'id_escola' => 2
            ],
        ]);
    }
}
