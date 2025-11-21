<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ControleParental;

class ControleParentalSeeder extends Seeder
{
    public function run()
    {
        ControleParental::insert([
            ['id_responsavel' => 2, 'id_aluno' => 1, 'ativo' => true, 'limite_diario' => 20.0, 'dias_semana' => 'Mon,Tue,Wed,Thu,Fri'],
        ]);
    }
}
