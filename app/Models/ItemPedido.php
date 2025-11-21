<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    use HasFactory;

    protected $table = 'tb_item_pedido';
    protected $primaryKey = 'id_item';
    public $timestamps = true;

    protected $fillable = [
        'id_pedido',
        'id_produto',
        'quantidade',
        'preco_unitario'
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }
}
