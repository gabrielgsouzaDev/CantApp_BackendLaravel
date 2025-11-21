<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedido extends Model
{
    protected $table = 'tb_item_pedido';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_pedido',
        'id_produto',
        'quantidade',
        'preco_unitario',
    ];

    protected $casts = [
        'id_pedido' => 'integer',
        'id_produto' => 'integer',
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
    ];

    public $timestamps = false;

    // Relacionamentos
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }
}
