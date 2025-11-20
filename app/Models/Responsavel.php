<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responsavel extends Model
{
    protected $table = 'responsaveis';

    protected $fillable = [
        'nome',
        'email',
        'senha_hash',
        'telefone'
    ];

    protected $hidden = [
        'senha_hash'
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'responsavel_id');
    }
}
