<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'tb_pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = true;

    protected $fillable = [
        'id_cantina',
        'id_comprador',
        'id_destinatario',
        'valor_total',
        'status'
    ];

    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    public function comprador()
    {
        return $this->belongsTo(User::class, 'id_comprador', 'id');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'id_destinatario', 'id');
    }

    public function itens()
    {
        return $this->hasMany(ItemPedido::class, 'id_pedido', 'id_pedido');
    }
}
