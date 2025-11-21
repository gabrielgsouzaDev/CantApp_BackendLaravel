<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Produto
 *
 * @property int $id_produto
 * @property int $id_cantina
 * @property string $nome
 * @property float $preco
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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

    // Relacionamentos
    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    public function pedidos()
    {
        return $this->belongsToMany(
            Pedido::class,
            'tb_item_pedido', // tabela pivot
            'id_produto',
            'id_pedido'
        )->withPivot('quantidade')->withTimestamps();
    }
}
