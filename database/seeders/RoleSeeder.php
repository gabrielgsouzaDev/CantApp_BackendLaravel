<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['nome_role' => 'Admin'],
            ['nome_role' => 'Aluno'],
            ['nome_role' => 'Responsavel'],
            ['nome_role' => 'Cantina'],
        ]);
    }
}
