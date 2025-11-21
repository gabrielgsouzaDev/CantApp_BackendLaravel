<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_user_role')->insert([
            ['id_user' => 1, 'id_role' => 1], // Admin
            ['id_user' => 2, 'id_role' => 2], // Gestor SP
            ['id_user' => 3, 'id_role' => 2], // Gestor RJ
            ['id_user' => 4, 'id_role' => 4], // Responsavel João
            ['id_user' => 5, 'id_role' => 4], // Responsavel Maria
            ['id_user' => 6, 'id_role' => 5], // Aluno Pedro
            ['id_user' => 7, 'id_role' => 5], // Aluno Ana
        ]);
    }
}
