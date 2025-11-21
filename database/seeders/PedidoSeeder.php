<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        Pedido::insert([
            ['id_cantina' => 1, 'id_comprador' => 1, 'id_destinatario' => 1, 'valor_total' => 13.50, 'status' => 'pendente'],
            ['id_cantina' => 2, 'id_comprador' => 1, 'id_destinatario' => 1, 'valor_total' => 4.50, 'status' => 'pendente'],
        ]);
    }
}
