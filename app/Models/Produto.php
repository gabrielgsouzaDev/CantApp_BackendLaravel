<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'tb_produto';
    protected $primaryKey = 'id_produto';

    protected $fillable = [
        'id_cantina',
        'nome',
        'preco',
        'ativo',
    ];

    protected $casts = [
        'id_cantina' => 'integer',
        'preco' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public $timestamps = true;

    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    public function pedidos()
    {
        return $this->belongsToMany(
            Pedido::class,
            'tb_item_pedido',
            'id_produto',
            'id_pedido'
        )->withPivot('quantidade', 'preco_unitario')
         ->withTimestamps();
    }
}
