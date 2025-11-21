<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        UserRole::insert([
            ['id_user' => 1, 'id_role' => 2], // Aluno
            ['id_user' => 2, 'id_role' => 3], // Responsavel
            ['id_user' => 3, 'id_role' => 1], // Admin
        ]);
    }
}
