<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DependenciaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_user_dependencia')->insert([
            ['id_responsavel' => 4, 'id_dependente' => 6],
            ['id_responsavel' => 5, 'id_dependente' => 7],
        ]);
    }
}
