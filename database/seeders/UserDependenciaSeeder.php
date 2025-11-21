<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDependencia;

class UserDependenciaSeeder extends Seeder
{
    public function run()
    {
        UserDependencia::insert([
            ['id_responsavel' => 2, 'id_dependente' => 1],
        ]);
    }
}
