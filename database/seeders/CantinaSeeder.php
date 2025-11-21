<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CantinaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_cantina')->insert([
            ['id_escola' => 1, 'nome' => 'Cantina SP', 'hr_abertura' => '07:00:00', 'hr_fechamento' => '15:00:00'],
            ['id_escola' => 2, 'nome' => 'Cantina RJ', 'hr_abertura' => '07:30:00', 'hr_fechamento' => '16:00:00'],
        ]);
    }
}
