<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carteira extends Model
{
    protected $table = 'tb_carteira';
    protected $primaryKey = 'id_carteira';

    protected $fillable = [
        'id_user',
        'saldo',
        'saldo_bloqueado',
        'limite_recarregar',
        'limite_maximo_saldo',
    ];

    protected $casts = [
        'id_user' => 'integer',
        'saldo' => 'decimal:2',
        'saldo_bloqueado' => 'decimal:2',
        'limite_recarregar' => 'decimal:2',
        'limite_maximo_saldo' => 'decimal:2',
    ];

    public $timestamps = true;

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class, 'id_carteira', 'id_carteira');
    }
}
