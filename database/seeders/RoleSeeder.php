<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_role')->insert([
            ['nome_role' => 'Admin', 'descricao' => 'Administrador do sistema'],
            ['nome_role' => 'Escola', 'descricao' => 'Administrador da escola'],
            ['nome_role' => 'Cantina', 'descricao' => 'Usuário responsável pela cantina'],
            ['nome_role' => 'Responsavel', 'descricao' => 'Responsável financeiro'],
            ['nome_role' => 'Aluno', 'descricao' => 'Aluno/consumidor'],
        ]);
    }
}
