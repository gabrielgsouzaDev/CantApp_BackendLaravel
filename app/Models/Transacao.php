<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'tb_transacao';
    protected $primaryKey = 'id_transacao';
    public $timestamps = true;

    protected $fillable = [
        'id_carteira',
        'id_user_autor',
        // CRÍTICO R10: Campos de log e rastreabilidade adicionados ao fillable
        'id_aprovador', 
        'uuid',         
        'tipo',
        'valor',
        'descricao',
        'referencia',   // Usado para ligar ao ID do Pedido/Recarga
        'status'        // Status da transação
    ];

    protected $casts = [
        'valor' => 'decimal:2'
    ];

    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'id_carteira', 'id_carteira');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'id_user_autor', 'id');
    }

    public function aprovador()
    {
        return $this->belongsTo(User::class, 'id_aprovador', 'id');
    }
}