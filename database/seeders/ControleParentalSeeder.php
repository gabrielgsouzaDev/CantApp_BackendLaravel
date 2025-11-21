<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ControleParentalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_controle_parental')->insert([
            ['id_responsavel' => 4, 'id_aluno' => 6, 'ativo' => true, 'limite_diario' => 20.00, 'dias_semana' => 'Seg,Ter,Qua,Qui,Sex'],
            ['id_responsavel' => 5, 'id_aluno' => 7, 'ativo' => true, 'limite_diario' => 15.00, 'dias_semana' => 'Seg,Ter,Qua,Qui,Sex'],
        ]);
    }
}
