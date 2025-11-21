<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cantina;

class CantinaSeeder extends Seeder
{
    public function run()
    {
        Cantina::insert([
            ['nome' => 'Cantina Central', 'id_escola' => 1, 'hr_abertura' => '07:00:00', 'hr_fechamento' => '17:00:00'],
            ['nome' => 'Cantina Nova', 'id_escola' => 2, 'hr_abertura' => '07:30:00', 'hr_fechamento' => '16:30:00'],
        ]);
    }
}
