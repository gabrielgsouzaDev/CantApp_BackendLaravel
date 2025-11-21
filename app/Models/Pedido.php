<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'tb_pedido';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_cantina',
        'id_comprador',
        'id_destinatario',
        'valor_total',
        'status',
    ];

    public $timestamps = true;

    public function comprador()
    {
        return $this->belongsTo(User::class, 'id_comprador', 'id');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'id_destinatario', 'id');
    }

    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    public function produtos()
    {
        return $this->belongsToMany(
            Produto::class,
            'tb_item_pedido',
            'id_pedido',
            'id_produto'
        )->withPivot('quantidade', 'preco_unitario')
         ->withTimestamps();
    }
}
