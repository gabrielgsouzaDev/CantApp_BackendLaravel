<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'tb_produto';
    protected $primaryKey = 'id_produto';
    public $timestamps = true;

    protected $fillable = [
        'id_cantina',
        'nome',
        'preco',
        'ativo', 
        'categoria'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'preco' => 'decimal:2',
        'categoria' => 'string'
    ];

    public function cantina()
    {
        return $this->belongsTo(Cantina::class, 'id_cantina', 'id_cantina');
    }

    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'id_produto', 'id_produto');
    }

    public function controleParental()
    {
        return $this->belongsToMany(
            ControleParental::class,
            'tb_controle_parental_produto',
            'id_produto',
            'id_controle'
        )->withPivot('permitido')->withTimestamps();
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'id_produto', 'id_produto');
    }
}
