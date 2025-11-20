<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = [
        'nome',
        'email',
        'senha_hash',
        'data_nascimento'
    ];

    protected $hidden = [
        'senha_hash'
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'aluno_id');
    }
}
