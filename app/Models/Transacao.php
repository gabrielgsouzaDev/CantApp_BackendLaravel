<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transacao extends Model
{
    protected $table = 'tb_transacao';
    protected $primaryKey = 'id_transacao';

    protected $fillable = [
        'id_carteira',
        'id_user_autor',
        'id_aprovador',
        'uuid',
        'tipo',
        'valor',
        'descricao',
    ];

    protected $casts = [
        'id_carteira' => 'integer',
        'id_user_autor' => 'integer',
        'id_aprovador' => 'integer',
        'valor' => 'decimal:2',
    ];

    public $timestamps = true;

    // Relacionamentos
    public function carteira(): BelongsTo
    {
        return $this->belongsTo(Carteira::class, 'id_carteira', 'id_carteira');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_autor', 'id');
    }

    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_aprovador', 'id');
    }
}
