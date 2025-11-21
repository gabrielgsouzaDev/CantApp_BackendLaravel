<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemPedido;

class ItemPedidoSeeder extends Seeder
{
    public function run()
    {
        ItemPedido::insert([
            ['id_pedido' => 1, 'id_produto' => 1, 'quantidade' => 2, 'preco_unitario' => 5.50],
            ['id_pedido' => 1, 'id_produto' => 2, 'quantidade' => 1, 'preco_unitario' => 8.00],
            ['id_pedido' => 2, 'id_produto' => 3, 'quantidade' => 1, 'preco_unitario' => 4.50],
        ]);
    }
}
