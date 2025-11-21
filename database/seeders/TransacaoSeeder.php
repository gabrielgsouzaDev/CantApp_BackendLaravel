<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;

class TransacaoSeeder extends Seeder
{
    public function run()
    {
        Transacao::insert([
            ['id_carteira' => 1, 'id_user_autor' => 2, 'id_aprovador' => null, 'uuid' => 'uuid-123', 'tipo' => 'credito', 'valor' => 50.00, 'descricao' => 'Recarga inicial', 'referencia' => 'recarga_001', 'status' => 'aprovado'],
        ]);
    }
}
